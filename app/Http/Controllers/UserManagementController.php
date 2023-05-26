<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserManagementController extends Controller
{
    public function index()
    {
       if(Auth::user()->role_name == 'Admin') {
            $users = DB::table('users')->get();
            $role_name   = DB::table('role_type_users')->get();
            $position    = DB::table('position_types')->get();
            $department  = DB::table('departments')->get();
            $status_user = DB::table('user_types')->get();
            return view('usermanagement.user_control', compact('users', 'role_name', 'department', 'position', 'status_user'));
       }else {
           return redirect()->route('home');
       }
    }
}
