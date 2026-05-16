<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function adminDashboard()
    {
        return view('admin.dashboard', [
            'totalUsers' => \App\Models\User::count(),
            'users' => \App\Models\User::all(),
        ]);
    }
}
