<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PortfolioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $portfolio = DB::table('portfolio')->orderBy('id', 'asc')->first();
        // dd($portfolio);
        return view('admin.shop.portfolio.index', [
            'portfolio' => $portfolio,
            'pageTitle' => __('messages.portfolio'),
            'breadcrumbs' => [
                ['label' => __('messages.dashboard'), 'url' => '/admin', 'active' => false],

                ['label' => __('messages.portfolio'), 'url' => '', 'active' => true],
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
        $validated = $request->validate([
            'full_name'       => 'required|string|max:255',
            'location' => 'nullable|string',
        ]);

        $updated = DB::table('portfolio')
            ->where('id', $id)
            ->update([
                'full_name'       => $validated['full_name'],
                'location' => $validated['location'],
                'updated_at'  => now(),
            ]);


        return redirect()->route('portfolio.index')->with('success', 'Portfolio updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
