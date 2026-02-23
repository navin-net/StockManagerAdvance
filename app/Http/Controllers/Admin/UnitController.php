<?php

namespace App\Http\Controllers\Admin;

use App\Models\Units;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Units::select(['id', 'name', 'slug']);
            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    return '
                        <button class="btn btn-primary btn-sm editUnitsBtn" data-id="' . $row->id . '">Edit</button>
                        <button class="btn btn-danger btn-sm deleteUnitsBtn" data-id="' . $row->id . '">Delete</button>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        // pp($request->all());
        return view('admin.settings.units.index', [
            'pageTitle' => __('messages.units_list'),
            'heading' => __('messages.stock_management_system'),
            'description' => __('messages.dashboard_welcome'),
            'breadcrumbs' => [
                ['label' => __('messages.dashboard'), 'url' => '/admin/dashboard', 'active' => false],
                ['label' => __('messages.units'), 'url' => '', 'active' => true],
            ]
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
        ]);

        $units = Units::create($validated);

        return response()->json([
            'success' => true,
            'message' => __('messages.units_added_successfully'),
            'units' => $units
        ]);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $units = Units::findOrFail($id);
        return response()->json($units); // This must be JSON\
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $units = Units::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
        ]);

        $units->update($validated);

        return response()->json([
            'success' => true,
            'message' => __('messages.units_updated_successfully'),
            'units' => $units
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $unit = Units::findOrFail($id);

        $unit->delete();

        return response()->json([
            'status' => true,
            'message' => 'Unit deleted successfully.'
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        $units = Units::whereIn('id', $ids)->get();

        foreach ($units as $unit) {
            $unit->delete();
        }
        return response()->json([
            'status' => true,
            'message' => 'selected_units_deleted_successfully.'
        ]);
    }


}
