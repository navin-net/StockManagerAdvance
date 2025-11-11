<?php

namespace App\Http\Controllers\Admin;

use App\Models\Qualitys;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
                        <button class="btn btn-primary btn-sm editQuality" data-id="' . $row->id . '">Edit</button>
                        <button class="btn btn-danger btn-sm deleteQuality" data-id="' . $row->id . '">Delete</button>
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
        //
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
