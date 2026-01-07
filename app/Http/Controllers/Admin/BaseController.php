<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\{Auth, View};

class BaseController extends Controller
{
    public function __construct()
    {
        view()->share('pageTitle', 'Admin Panel');
    }
}
