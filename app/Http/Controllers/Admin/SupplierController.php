<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Companies;
use App\Models\Groups;
use App\Models\Warehouses;
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
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:companies,email',
            'address' => 'required|max:255',
            'phone' => 'required|max:20',
            'city' => 'nullable|string|max:255',
            'street' => 'nullable|string|max:255',
            'number_of_houses' => 'nullable|string|max:50',
            'logo' => 'nullable|image|max:2048',
        ]);

        $logoPath = null;

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        Companies::create([
            'name' => $request->name,
            'email' => $request->email,
            'city' => $request->city,
            'number_of_houses' => $request->number_of_houses,
            'street' => $request->street,
            'address' => $request->address,
            'phone' => $request->phone,
            'group_id' => 5,
            'group_name' => 'Supplier',
            'logo' => $logoPath,
        ]);

        return redirect()->route('suppliers.index')->with('success', __('messages.suppliers_created_successfully'));


    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
