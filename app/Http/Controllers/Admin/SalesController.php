<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Validator};
use App\Http\Controllers\Controller;
use App\Models\{Payment, Products, Sale, SaleItem};
use Yajra\DataTables\Facades\DataTables;

class SalesController extends Controller
{
    public function index()
    {
        $pageTitle = __('messages.sales_list');
        $breadcrumbs = [['label' => __('messages.dashboard'), 'url' => route('dashboard'), 'active' => false], ['label' => __('messages.sales'), 'url' => '', 'active' => true]];
        $products = Products::select('id', 'name', 'code')->get();
        return view('admin.sales.index', compact('pageTitle', 'breadcrumbs', 'products'));
    }

    public function getData(Request $request)
    {
        $query = Sale::withCount('items')->withSum('items', 'quantity')->orderBy('date', 'desc');
        return DataTables::of($query)
            ->addColumn('action', function ($sale) {
                return '
                    <div class="dropdown">
                        <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton' .$sale->id .'" data-bs-toggle="dropdown" aria-expanded="false" title="Actions">Actions
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton' .$sale->id .'">
                            <li>
                                <a class="dropdown-item" href="' .route('sales.show', $sale->id) .'"><i class="bi bi-eye me-2"></i> Show</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="' .route('sales.edit', $sale->id) .'"><i class="bi bi-pencil me-2"></i> Edit</a>
                            </li>
                            <li>
                                <button class="dropdown-item payment-sale" data-id="'.$sale->id.'" type="button">
                                    <i class="bi bi-wallet2 me-2"></i> Add Payment
                                </button>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li><button class="dropdown-item text-danger delete-sale" data-id="' .$sale->id .'" type="button">
                                    <i class="bi bi-trash me-2"></i> Delete
                                </button>
                            </li>
                        </ul>
                    </div>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        $products = Products::select('id', 'name', 'code', 'stock_quantity', 'selling_price')->get();
        return view('admin.sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|string|max:255',
            'date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.sale_price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::transaction(function () use ($request) {
            $sale = Sale::create([
                'customer_id' => null,
                'total_amount' => $request->total_amount,
                'status' => $request->status,
                'reference' => 'SALE-' . strtoupper(uniqid()),
                'date' => $request->date,
            ]);

            foreach ($request->items as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'sale_price' => $item['sale_price'],
                ]);

                Products::where('id', $item['product_id'])->decrement('stock_quantity', $item['quantity']);
            }
        });

        return response()->json(
            [
                'message' => __('messages.sale_created_successfully'),
                'redirect' => route('sales.index'),
            ],
            201,
        );
    }

    public function show($id)
    {
        $sale = Sale::with('items.product')->findOrFail($id);

        return view('admin.sales.show', compact('sale'));
    }

    public function payments($id)
    {
        // $sale = Sale->findOrFail($id);
        $sale = Sale::findOrFail($id);


        return response()->json(data: ['sale' => $sale]);

    }

    public function storePayment(Request $request)
    {
        $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
        ]);

    Payment::create([
        'sale_id'   => $request->sale_id,
        'method'    => 'cash',
        'amount'    => $request->amount,
        'paid_at'   => $request->date,             // match your input name
        'reference' => $request->reference ?? null,
    ]);


        $sale = Sale::with('payments')->find($request->sale_id);

        return response()->json([
            'status' => true,
            'message' => 'Payment added successfully!',
            'paid' => $sale->payments->sum('amount'),
            'due' => $sale->total_amount - $sale->payments->sum('amount')
        ]);
    }


    public function edit($id)
    {
        $sale = Sale::with('items.product')->findOrFail($id);
        $products = Products::select('id', 'name', 'code', 'stock_quantity', 'selling_price')->get();
        return view('admin.sales.edit', compact('sale', 'products'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|string|max:255',
            'date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.sale_price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::transaction(function () use ($request, $id) {
            $sale = Sale::findOrFail($id);

            // Restore stock for old items
            foreach ($sale->items as $item) {
                Products::where('id', $item->product_id)->increment('stock_quantity', $item->quantity);
            }

            // Update sale
            $sale->update([
                'total_amount' => $request->total_amount,
                'status' => $request->status,
                'date' => $request->date,
            ]);

            // Delete old items
            $sale->items()->delete();

            // Create new items
            foreach ($request->items as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'sale_price' => $item['sale_price'],
                ]);

                // Update stock (allows negative stock)
                Products::where('id', $item['product_id'])->decrement('stock_quantity', $item['quantity']);
            }
        });

        return response()->json([
            'message' => __('messages.sale_updated_successfully'),
            'redirect' => route('sales.index'),
        ]);
    }
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $sale = Sale::findOrFail($id);
            foreach ($sale->items as $item) {
                Products::where('id', $item->product_id)->increment('stock_quantity', $item->quantity);
            }
            $sale->items()->delete();
            $sale->delete();
        });

        return response()->json(['message' => __('messages.sale_deleted_successfully')]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        DB::transaction(function () use ($ids) {
            foreach ($ids as $id) {
                $sale = Sale::find($id);
                if ($sale) {
                    foreach ($sale->items as $item) {
                        Products::where('id', $item->product_id)->increment('stock_quantity', $item->quantity);
                    }
                    $sale->items()->delete();
                    $sale->delete();
                }
            }
        });

        return response()->json(['message' => __('messages.selected_sales_deleted_successfully')]);
    }

    // public function export(Request $request)
    // {
    //     $ids = $request->query('ids') ? explode(',', $request->query('ids')) : null;
    //     return Excel::download(new SalesExport($ids), 'sales_' . now()->format('Y-m-d') . '.xlsx');
    // }
}
