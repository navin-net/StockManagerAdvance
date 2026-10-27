<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Companies;
use App\Models\Groups;
use App\Models\Warehouses;
use App\Models\Purchase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $query = DB::table('companies')
                ->leftJoin('groups', 'companies.group_id', '=', 'groups.id')
                ->select(
                    'companies.id',
                    'companies.name',
                    'companies.email',
                    'companies.city',
                    'companies.number_of_houses',
                    'companies.street',
                    'companies.address',
                    'companies.phone',
                    'groups.name as group_name'
                )->where('companies.group_id', 5);
            return DataTables::of($query)
                ->addColumn('action', function ($row) {
                    return '
                    <div class="dropdown">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton' . $row->id . '" data-bs-toggle="dropdown" aria-expanded="false">
                        ' . __('messages.actions') . '
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $row->id . '">
                            <li>
                                    <a class="dropdown-item" href="' . route('suppliers.edit', $row->id) . '" title="' . __('messages.edit') . '">
                                    <i class="bi bi-pencil-square me-2"></i>' . __('messages.edit') . '
                                </a>
                            </li>

                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger deleteBillerBtn" href="#" data-id="' . $row->id . '" title="' . __('messages.delete') . '">
                                    <i class="bi bi-trash me-2"></i>' . __('messages.delete') . '
                                </a>
                            </li>
                        </ul>
                    </div>
                    ';
                })
                ->filterColumn('group_name', function ($query, $keyword) {
                    $query->where('groups.name', 'like', "%$keyword%");
                })

                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.suppliers.index', [
            'pageTitle' => __('messages.list_suppliers'),
            'breadcrumbs' => [
                ['label' => __('messages.dashboard'), 'url' => '#', 'active' => false],
                ['label' => __('messages.suppliers'), 'url' => '', 'active' => true],
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companies = Companies::all();
        $groups = Groups::find(5);
        $warehouses = Warehouses::all();
//        $companies = DB::table('companies')->select('*')->get();
//        $groups = DB::table('groups')->select('id', 'name')->get();


        return view('admin.suppliers.create', [
            'groups' => $groups,
            'warehouse' => $warehouses,
            'companies' => $companies,
            'pageTitle' => __('messages.create'),
            'breadcrumbs' => [
                ['label' => __('messages.dashboard'), 'url' => '/admin/dashboard', 'active' => false],
                ['label' => __('messages.suppliers'), 'url' => '/admin/suppliers', 'active' => false],
                ['label' => __('messages.create'), 'url' => '', 'active' => true],
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }
        Companies::create($data + ['group_id' => 5, 'group_name' => 'Supplier']);
        return redirect()->route('suppliers.index')->with('success', __('messages.suppliers_created_successfully'));
    }

    public function edit(string $id)
    {
        $supplier = Companies::where('group_id', 5)->findOrFail($id);
        return view('admin.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, string $id)
    {
        $supplier = Companies::where('group_id', 5)->findOrFail($id);
        $data = $this->validatedData($request, $supplier->id);
        $oldLogo = $supplier->logo;
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }
        $supplier->update($data);
        if (isset($data['logo']) && $oldLogo) {
            Storage::disk('public')->delete($oldLogo);
        }
        return redirect()->route('suppliers.index')->with('success', __('messages.updated_successfully'));
    }

    public function destroy(string $id)
    {
        $this->deleteSuppliers([(int) $id]);
        return response()->json(['success' => __('messages.supplier_deleted')]);
    }

    public function bulkDelete(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'required|integer|distinct|exists:companies,id',
        ]);
        $this->deleteSuppliers($data['ids']);
        return response()->json(['success' => __('messages.supplier_deleted')]);
    }

    private function validatedData(Request $request, $id = null): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', \Illuminate\Validation\Rule::unique('companies', 'email')->ignore($id)],
            'address' => 'required|string|max:255', 'phone' => 'required|string|max:20',
            'city' => 'nullable|string|max:255', 'street' => 'nullable|string|max:255',
            'number_of_houses' => 'nullable|string|max:50', 'note' => 'nullable|string|max:5000',
            'logo' => 'nullable|image|max:2048',
        ]);
    }

    private function deleteSuppliers(array $ids): void
    {
        $logos = DB::transaction(function () use ($ids) {
            $logos = [];
            foreach (collect($ids)->sort()->values() as $id) {
                $supplier = Companies::where('group_id', 5)->whereKey($id)->lockForUpdate()->firstOrFail();
                abort_if($supplier->users()->exists() || Purchase::where('supplier_id', $id)->exists(),
                    422, __('messages.supplier_has_transactions'));
                if ($supplier->logo) {
                    $logos[] = $supplier->logo;
                }
                $supplier->delete();
            }
            return $logos;
        });
        Storage::disk('public')->delete($logos);
    }
}
