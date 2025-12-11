<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Groups;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class GroupsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Groups::select(['id', 'name']);

            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    return '
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button"
                                class="btn btn-sm btn-outline-primary editGroup"
                                data-id="'.$row->id.'"
                                title="Edit">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button type="button"
                                class="btn btn-sm btn-outline-danger deleteGroup"
                                data-id="'.$row->id.'"
                                title="Delete">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
                // die($data);
        }

        return view('admin.settings.groups.index', [
            'pageTitle' => __('messages.groups_list'),
            'heading' => __('messages.stock_management_system'),
            'description' => __('messages.dashboard_welcome'),
            'breadcrumbs' => [
                ['label' => __('messages.dashboard'), 'url' => '/admin/dashboard', 'active' => false],

                ['label' => __('messages.groups'), 'url' => '', 'active' => true],
            ],
        ]);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
        ]);

        $group = Groups::create($validated);

        return response()->json([
            'success'   => true,
            'message'   => 'Group added successfully',
            'group' => $group
        ]);
    }
    public function edit($id)
    {
        $group = Groups::findOrFail($id);
        return response()->json(['group' => $group]);
    }
    public function update(Request $request, $id)
    {
        $group = Groups::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $group->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Group updated successfully',
            'group'   => $group
        ]);
    }

    public function destroy($id)
    {
        $group = Groups::findOrFail($id);
        $group->delete();

        return response()->json([
            'success' => true,
            'message' => 'Group deleted successfully'
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:groups,id', // Validate each ID exists in the groups table
        ]);

        // Delete groups by IDs
        Groups::whereIn('id', $request->ids)->delete();

        return response()->json(['success' => 'Selected groups deleted successfully.']);
    }

}
