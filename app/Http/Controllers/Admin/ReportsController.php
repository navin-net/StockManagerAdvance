<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function monthly_sales()
    {
        return view('admin.reports.monthly-sales');
    }

    public function daily_sales()
    {
        return view('admin.reports.daily-sales');
    }
}
