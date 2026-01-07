<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::select([
                    'users.id',
                    'users.name',
                    'users.email',
                    'groups.name as group_name'
                ])
                ->leftJoin('groups', 'users.group_id', '=', 'groups.id')
                ->where('users.company_id', NULL);

            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    return '
                        <button class="btn btn-primary btn-sm editUser" data-id="' . $row->id . '">' . __('messages.edit') . '</button>
                        <button class="btn btn-danger btn-sm deleteUser" data-id="' . $row->id . '">' . __('messages.delete') . '</button>
                    ';
                })
                ->filterColumn('group_name', function($query,$keyword){
                    $query->where('groups.name','like',"%$keyword%");
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $groups = DB::table('groups')->select('id', 'name')->get();
        return view('admin.users.index', [
            'pageTitle' => __('messages.list_users'),
            'groups' => $groups,
            'breadcrumbs' => [
                ['label' => __('messages.dashboard'), 'url' => '#', 'active' => false],
                ['label' => __('messages.users'), 'url' => '', 'active' => true],
            ]
        ]);
    }

    public function create()
    {
        $groups     = DB::table('groups')->select('id', 'name')->get();
        return view('admin.users.create',  [
            'groups' => $groups,
            'pageTitle' => __('messages.list_users'),
            'breadcrumbs' => [
                ['label' => __('messages.users'), 'url' => '#', 'active' => false],
                ['label' => __('messages.add'), 'url' => '', 'active' => true],
            ]
        ]);
    }

}
