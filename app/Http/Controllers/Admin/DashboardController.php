<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // Dashboard View
    public function index()
    {
        return view('admin.dashboard.dashboard');
    }

    // Admin Logout
    public function adminLogout()
    {
        Auth::logout();
        session()->flush();
        return redirect()->route('adminlogin');
    }
}
