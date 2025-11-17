<?php

namespace App\Http\Controllers\Admin;

use App\Models\Warehouses;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Warehouses::select(['id', 'name', 'location', 'note']);

            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    return '
                    <div class="d-flex justify-content-center gap-2">
                        <button class="btn btn-sm btn-primary editWarehouse action-btn" data-id="' . $row->id . '">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-danger deleteWarehouse action-btn" data-id="' . $row->id . '">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.settings.warehouse.index', [
            'pageTitle' => __('messages.warehouse_list'),
            'heading' => __('messages.stock_management_system'),
            'description' => __('messages.dashboard_welcome'),
            'breadcrumbs' => [
                ['label' => __('messages.dashboard'), 'url' => '/admin/dashboard', 'active' => false],
                
                ['label' => __('messages.warehouse'), 'url' => '', 'active' => true],
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'note'     => 'nullable|string|max:255',
        ]);

        $warehouse = Warehouses::create($validated);

        return response()->json([
            'success'   => true,
            'message'   => 'Warehouse added successfully',
            'warehouse' => $warehouse
        ]);
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
