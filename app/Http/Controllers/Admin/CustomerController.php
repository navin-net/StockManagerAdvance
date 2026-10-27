<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Companies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
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
                )->where('companies.group_id', 4);
            return DataTables::of($query)
                ->addColumn('action', function ($row) {
                    return '
                    <div class="dropdown">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton' . $row->id . '" data-bs-toggle="dropdown" aria-expanded="false">
                        ' . __('messages.actions') . '
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $row->id . '">
                            <li>
                                    <a class="dropdown-item" href="' . route('customers.edit', $row->id) . '" title="' . __('messages.edit') . '">
                                    <i class="bi bi-pencil-square me-2"></i>' . __('messages.edit') . '
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="' . route('customers.users.add', $row->id) . '" title="' . __('messages.add_user') . '">
                                    <i class="bi bi-person-plus me-2"></i>' . __('messages.add_user') . '
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item listUser" href="#" data-id="' . $row->id . '" title="' . __('messages.list_user') . '">
                                    <i class="bi bi-people me-2"></i>' . __('messages.list_user') . '
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
        return view('admin.customers.index', [
            'pageTitle' => __('messages.customers_list'),
            'breadcrumbs' => [
                ['label' => __('messages.dashboard'), 'url' => '#', 'active' => false],
                ['label' => __('messages.customers'), 'url' => '', 'active' => true],
            ]
        ]);
    }

    public function create()
    {
        $companies = DB::table('companies')->select('*')->get();
        $groups = DB::table('groups')->select('id', 'name')->get();
        $warehouses = DB::table('warehouses')->select('id', 'name')->get();


        return view('admin.customers.create', [
            'groups' => $groups,
            'warehouse' => $warehouses,
            'companies' => $companies,
            'pageTitle' => __('messages.create'),
            'breadcrumbs' => [
                ['label' => __('messages.dashboard'), 'url' => '/admin/dashboard', 'active' => false],
                ['label' => __('messages.customers'), 'url' => '/admin/customers', 'active' => false],
                ['label' => __('messages.create'), 'url' => '', 'active' => true],
            ]
        ]);
    }

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
            'group_id' => 4,
            'group_name' => 'Customer',
            'logo' => $logoPath,
        ]);

        return redirect()->route('customers.index')->with('success', __('messages.customers_created_successfully'));


    }

    public function edit($id)
    {
        $customer = Companies::where('group_id', 4)->findOrFail($id);
        return view('admin.customers.edit', [
            'customer' => $customer,
            'pageTitle' => __('messages.edit'),
            'breadcrumbs' => [
                ['label' => __('messages.dashboard'), 'url' => '/admin/dashboard', 'active' => false],
                ['label' => __('messages.customers'), 'url' => '/admin/customers', 'active' => false],
                ['label' => __('messages.edit'), 'url' => '', 'active' => true],
            ]
        ]);
    }



    public function update(Request $request, $id)
    {
        $customer = Companies::where('group_id', 4)->findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:255', 'email' => 'required|email|unique:companies,email,' . $customer->id,
            'address' => 'required|string|max:255', 'phone' => 'required|string|max:20',
            'city' => 'nullable|string|max:255', 'street' => 'nullable|string|max:255',
            'number_of_houses' => 'nullable|string|max:50',
        ]);
        $customer->update($data);
        return redirect()->route('customers.index')->with('success', 'Customer updated');
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $customer = Companies::where('group_id', 4)->whereKey($id)->lockForUpdate()->firstOrFail();
            abort_if($customer->users()->exists() || \App\Models\Sale::where('customer_id', $id)->exists(), 422, 'Customers with users or sales must be retained');
            $customer->delete();
        });
        return response()->json(['success' => 'Customer deleted']);
    }

    public function listUsers($id)
    {
        $customer = Companies::where('group_id', 4)->with('users')->findOrFail($id);
        return view('admin.customers.users', compact('customer'));
    }

    public function addUser($id)
    {
        $customer = Companies::where('group_id', 4)->findOrFail($id);
        return view('admin.customers.user-form', compact('customer'));
    }

    public function storeUser(Request $request, $id)
    {
        $customer = Companies::where('group_id', 4)->findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:255', 'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $customer->users()->create($data + ['group_id' => 4, 'status' => 'active']);
        return redirect()->route('customers.index')->with('success', 'Customer account created');
    }

    public function editUser($id)
    {
        $user = \App\Models\User::where('group_id', 4)->whereHas('company', fn ($q) => $q->where('group_id', 4))->findOrFail($id);
        $customer = $user->company;
        return view('admin.customers.user-form', compact('customer', 'user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = \App\Models\User::where('group_id', 4)->whereHas('company', fn ($q) => $q->where('group_id', 4))->findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:255', 'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        if (empty($data['password'])) {
            unset($data['password']);
        }
        $user->update($data);
        if (isset($data['password'])) {
            $user->tokens()->delete();
        }
        return redirect()->route('customers.index')->with('success', 'Customer account updated');
    }

    public function deleteUser($id)
    {
        $user = \App\Models\User::where('group_id', 4)->whereHas('company', fn ($q) => $q->where('group_id', 4))->findOrFail($id);
        abort_if(\App\Models\Sale::where('user_id', $user->id)->exists(), 422, 'Accounts with sales must be retained');
        DB::transaction(function () use ($user) {
            $user->tokens()->delete();
            $user->delete();
        });
        return response()->json(['success' => 'Customer account deleted']);
    }
}
