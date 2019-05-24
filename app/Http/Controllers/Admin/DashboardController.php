<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:admin');
    }
    
    public function __invoke()      
    {
        return view('admin.index')->withTitle('Dashboard Page');
    }
}
