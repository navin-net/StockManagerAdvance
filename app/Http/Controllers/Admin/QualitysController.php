<?php

namespace App\Http\Controllers\Admin;

use App\Models\Qualitys;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Ramsey\Uuid\Type\Integer;
use Yajra\DataTables\Facades\DataTables;

class QualitysController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Qualitys::select(['id', 'name', 'description']);
            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    return '
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button"
                                class="btn btn-sm btn-outline-primary editQuality"
                                data-id="'.$row->id.'"
                                title="Edit">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button type="button"
                                class="btn btn-sm btn-outline-danger deleteQuality"
                                data-id="'.$row->id.'"
                                title="Delete">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.settings.qualitys.index', [
            'pageTitle' => __('messages.qualitys_list'),
            'heading' => __('messages.stock_management_system'),
            'description' => __('messages.dashboard_welcome'),
            'breadcrumbs' => [
                ['label' => __('messages.dashboard'), 'url' => '/admin/dashboard', 'active' => false],

                ['label' => __('messages.qualitys'), 'url' => '', 'active' => true],
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
        ]);

        $quality = Qualitys::create($validated);

        return response()->json([
            'success'   => true,
            'message'   => 'Quality added successfully',
            'quality' => $quality
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
        $quality = Qualitys::findOrFail($id);
        return response()->json(['quality' => $quality]);


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $quality = Qualitys::findOrFail($id);
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
        ]);
        $quality->update($validated);

        return response()->json([
            'success'   => true,
            'message'   => 'Quality updated successfully',
            'quality'   => $quality
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Qualitys::destroy($id);
        return response()->json([
            'success'   => true,
            'message'   => 'Quality deleted successfully',
            'destroy'   => true
        ]);

    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:quality,id', // Validate each ID exists in the quality table
        ]);

        Qualitys::whereIn('id', $request->ids)->delete();

        return response()->json(['success' => 'Selected quality deleted successfully.']);

    }

    }
