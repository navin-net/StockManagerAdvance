<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class BillerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = DB::table('companies')
                ->leftJoin('groups', 'companies.group_id', '=', 'groups.id')
                ->leftJoin('warehouses', 'companies.warehouse_id', '=', 'warehouses.id')
                ->select(
                    'companies.id',
                    'companies.name',
                    'companies.email',
                    'companies.city',
                    'companies.number_of_houses',
                    'companies.street',
                    'companies.address',
                    'companies.phone',
                    'groups.name as group_name',
                    'warehouses.name as warehouse_name'
                )->where('companies.group_id', 2);
            return DataTables::of($query)
                ->addColumn('action', function ($row) {
                    return '
                    <div class="dropdown">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton' . $row->id . '" data-bs-toggle="dropdown" aria-expanded="false">
                        ' . __('messages.action') . '
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $row->id . '">
                            <li>
                                <a class="dropdown-item" href="' . route('billers.users.add', $row->id) . '" title="' . __('messages.add_user') . '">
                                    <i class="bi bi-person-plus me-2"></i>' . __('messages.add_user') . '
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item listUser" href="#" data-id="' . $row->id . '" title="' . __('messages.list_user') . '">
                                    <i class="bi bi-people me-2"></i>' . __('messages.list_user') . '
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="' . url('billers/' . $row->id . '/edit') . '" title="' . __('messages.edit') . '">
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
                    $query->where('groups.name', 'like', "%{$keyword}%");
                })
                ->filterColumn('warehouse_name', function ($query, $keyword) {
                    $query->where('warehouses.name', 'like', "%{$keyword}%");
                })
                ->orderColumn('group_name', 'groups.name $1')
                ->orderColumn('warehouse_name', 'warehouses.name $1')
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.billers.index', [
            'pageTitle' => __('messages.list_billers'),
            // 'groups' => $groups,
            'breadcrumbs' => [
                ['label' => __('messages.dashboard'), 'url' => '#', 'active' => false],
                ['label' => __('messages.billers'), 'url' => '', 'active' => true],
            ]
        ]);
    }

    public function create()
    {
        $companies = DB::table('companies')->select('*')->get();
        $groups = DB::table('groups')->select('id', 'name')->get();
        $warehouses = DB::table('warehouses')->select('id', 'name')->get();

        // pp($companies);
        return view('admin.billers.create',  [
            'groups' => $groups,
            'warehouse' => $warehouses,
            'companies' => $companies,
            'pageTitle' => __('messages.list_billers'),
            'breadcrumbs' => [
                ['label' => __('messages.billers'), 'url' => '#', 'active' => false],
                ['label' => __('messages.add'), 'url' => '', 'active' => true],
            ]
        ]);    
    }

}
