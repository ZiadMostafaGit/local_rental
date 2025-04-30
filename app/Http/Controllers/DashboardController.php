<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function index()
    {
        return view('admin_dashboard.pages.dashboard');
    }

}
