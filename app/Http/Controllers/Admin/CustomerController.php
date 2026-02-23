<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
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
                        ' . __('messages.action') . '
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
                ->filterColumn('group_name', function($query,$keyword){
                    $query->where('groups.name','like',"%$keyword%");
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
        return view('admin.customers.create', [
            'pageTitle' => __('messages.create'),
            'breadcrumbs' => [
                ['label' => __('messages.dashboard'), 'url' => '/admin/dashboard', 'active' => false],
                ['label' => __('messages.customers'), 'url' => '/admin/customers', 'active' => false],
                ['label' => __('messages.create'),'url' => '', 'active' => true],
            ]
        ]);
    }

    public function edit($id)
    {
        return view('admin.customers.edit', [
            'pageTitle' => __('messages.edit'),
            'breadcrumbs' => [
                ['label' => __('messages.dashboard'), 'url' => '/admin/dashboard', 'active' => false],
                ['label' => __('messages.customers'), 'url' => '/admin/customers', 'active' => false],
                ['label' => __('messages.edit'),'url' => '', 'active' => true],
            ]
        ]);
    }
}
