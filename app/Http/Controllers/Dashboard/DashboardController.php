<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $admins_count = User::whereHasRole('admin');
        if (hasRole('owner')) {
            $admins_count = $admins_count->orWhereHasRole("owner");
        }
        $admins_count = $admins_count->get(['id'])->count();

        return view("dashboard.index", compact([
            'admins_count'
        ]));
    }
}
