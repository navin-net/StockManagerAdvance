<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Companies, Products, Purchase};
use App\Services\CsvExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Storage};
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class PurchasesController extends Controller
{
    public function index()
    {
        return view('admin.purchases.index', $this->viewData());
    }

    public function getData()
    {
        $query = Purchase::with('supplier')->withSum(['payments as paid' => fn ($q) => $q->where('status', 'completed')], 'amount');
        return DataTables::of($query)
            ->addColumn('grand_total', fn ($purchase) => $purchase->total_amount)
            ->addColumn('balance', fn ($purchase) => round($purchase->total_amount - ($purchase->paid ?? 0), 2))
            ->addColumn('supplier', fn ($purchase) => $purchase->supplier?->name)
            ->addColumn('action', fn ($row) => view('admin.purchases.partials.actions', compact('row'))->render())
            ->rawColumns(['action'])->make(true);
    }

    public function create()
    {
        return view('admin.purchases.create', $this->viewData());
    }

    public function edit($id)
    {
        return view('admin.purchases.edit', $this->viewData() + ['purchase' => Purchase::with('items.product')->findOrFail($id)]);
    }

    public function show($id)
    {
        return view('admin.purchases.show', $this->viewData() + ['purchase' => Purchase::with('items.product', 'supplier', 'payments')->findOrFail($id)]);
    }

    public function store(Request $request)
    {
        $this->persist($request);
        return redirect()->route('purchases.index')->with('success', __('messages.purchase_created_successfully'));
    }

    public function update(Request $request, $id)
    {
        $this->persist($request, $id);
        return redirect()->route('purchases.index')->with('success', 'Purchase updated');
    }

    public function destroy($id)
    {
        DB::transaction(fn () => $this->deletePurchase($id), 3);
        return response()->json(['message' => 'Purchase deleted']);
    }

    public function bulkDelete(Request $request)
    {
        $data = $request->validate(['ids' => 'required|array|min:1', 'ids.*' => 'required|integer|distinct|exists:purchases,id']);
        DB::transaction(function () use ($data) {
            $ids = $data['ids'];
            sort($ids);
            foreach ($ids as $id) {
                $this->deletePurchase($id);
            }
        }, 3);
        return response()->json(['message' => 'Purchases deleted']);
    }

    public function export(CsvExportService $export)
    {
        return $export->download('purchases.csv', ['Reference', 'Date', 'Total', 'Status'],
            Purchase::orderBy('id')->cursor()->map(fn ($p) => [$p->reference, $p->date, $p->total_amount, $p->status]));
    }

    private function viewData(): array
    {
        return [
            'products' => Products::select('id', 'name', 'code', 'stock_quantity', 'cost_price')->get(),
            'suppliers' => Companies::where('group_id', 5)->get(),
            'pageTitle' => __('messages.purchases_list'),
            'heading' => __('messages.purchases_list'),
            'breadcrumbs' => [
                ['label' => __('messages.dashboard'), 'url' => route('admin.dashboard'), 'active' => false],
                ['label' => __('messages.purchases'), 'url' => route('purchases.index'), 'active' => true],
            ],
        ];
    }

    private function persist(Request $request, $id = null): Purchase
    {
        $data = $request->validate([
            'supplier_id' => 'required|exists:companies,id',
            'status' => 'required|in:pending,completed,cancelled',
            'date' => 'required|date',
            'note' => 'nullable|string|max:5000',
            'attachments' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|distinct|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1|max:1000000',
            'items.*.cost_price' => 'required|numeric|min:0|max:999999.99',
        ]);
        abort_unless(Companies::whereKey($data['supplier_id'])->where('group_id', 5)->exists(), 422, 'Select a supplier');
        $path = $request->hasFile('attachments') ? $request->file('attachments')->store('purchases', 'public') : null;
        try {
            return DB::transaction(function () use ($data, $id, $path) {
                $purchase = $id ? Purchase::whereKey($id)->lockForUpdate()->firstOrFail() : new Purchase();
                $old = $purchase->exists ? $purchase->items()->get() : collect();
                $ids = collect($data['items'])->pluck('product_id')->merge($old->pluck('product_id'))->unique()->sort()->values();
                $products = Products::whereIn('id', $ids)->orderBy('id')->lockForUpdate()->get()->keyBy('id');
                $deltas = [];
                if ($purchase->exists && $purchase->status === 'completed') {
                    foreach ($old as $item) {
                        $deltas[$item->product_id] = ($deltas[$item->product_id] ?? 0) - $item->quantity;
                    }
                }
                if ($data['status'] === 'completed') {
                    foreach ($data['items'] as $item) {
                        $deltas[$item['product_id']] = ($deltas[$item['product_id']] ?? 0) + $item['quantity'];
                    }
                }
                foreach ($deltas as $productId => $delta) {
                    if ($products[$productId]->stock_quantity + $delta < 0) {
                        throw ValidationException::withMessages(['items' => 'This purchase stock has already been sold; its quantity cannot be reduced']);
                    }
                    $products[$productId]->increment('stock_quantity', $delta);
                }
                $total = collect($data['items'])->sum(fn ($item) => round($item['quantity'] * $item['cost_price'], 2));
                if ($total > 99999999.99) {
                    throw ValidationException::withMessages(['items' => 'Purchase total is too large']);
                }
                $paid = $purchase->exists ? $purchase->payments()->where('status', 'completed')->sum('amount') : 0;
                if ($paid > $total || ($paid > 0 && $data['status'] === 'cancelled')) {
                    throw ValidationException::withMessages(['items' => 'Refund payments before reducing or cancelling a paid purchase']);
                }
                $purchase->fill([
                    'reference' => $purchase->reference ?? 'PUR-' . \Illuminate\Support\Str::ulid(),
                    'supplier_id' => $data['supplier_id'], 'status' => $data['status'],
                    'date' => $data['date'], 'total_amount' => $total,
                    'note' => $data['note'] ?? null,
                    'attachments' => $path ?? $purchase->attachments,
                    'payment_status' => $paid >= $total ? 'paid' : ($paid > 0 ? 'partial' : 'unpaid'),
                ])->save();
                $purchase->items()->delete();
                foreach ($data['items'] as $item) {
                    $purchase->items()->create($item);
                }
                return $purchase;
            }, 3);
        } catch (\Throwable $e) {
            if ($path) {
                Storage::disk('public')->delete($path);
            }
            throw $e;
        }
    }

    private function deletePurchase($id): void
    {
        $purchase = Purchase::whereKey($id)->lockForUpdate()->firstOrFail();
        abort_if($purchase->payments()->exists(), 422, 'Purchases with payments must be retained');
        foreach ($purchase->items()->orderBy('product_id')->get() as $item) {
            $product = Products::whereKey($item->product_id)->lockForUpdate()->firstOrFail();
            if ($purchase->status === 'completed') {
                if ($product->stock_quantity < $item->quantity) {
                    throw ValidationException::withMessages(['purchase' => 'Purchase stock has already been sold']);
                }
                $product->decrement('stock_quantity', $item->quantity);
            }
        }
        $purchase->items()->delete();
        $purchase->delete();
    }
}
