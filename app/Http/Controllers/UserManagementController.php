<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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

    public function addNewUserSave(Request $request)
    {
        DB::beginTransaction();
        try {
            $dt = Carbon::now();
            $todayDate = $dt->toDayDateTimeString();

            $image = time().'.'.$request->image->extension();
            $request->image->move(public_path('assets/images'), $image);

            $user = new User();
            $user->name = $request->name;
            $user->email        = $request->email;
            $user->join_date    = $todayDate;
            $user->phone_number = $request->phone;
            $user->role_name    = $request->role_name;
            $user->position     = $request->position;
            $user->department   = $request->department;
            $user->status       = $request->status;
            $user->avatar       = $image;
            $user->password     = Hash::make($request->password);
            $user->save();
            DB::commit();
            return redirect()->route('userManagement')->with('success', 'Create new account successfully');
        }catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', 'User add new account fail');
        }
    }
}
