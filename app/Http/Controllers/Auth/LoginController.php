<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $username = $request->email;
        $password = $request->password;

        $dt = Carbon::now();
        $todayDate = $dt->toDayDateTimeString();

        if(Auth::attempt(['email' => $username, 'password' => $password, 'status' => 'Active'])) {
            $user = Auth::user();
            Session::put('name', $user->name);
            Session::put('email', $user->email);
            Session::put('rec_id', $user->rec_id);
            Session::put('join_date', $user->join_date);
            Session::put('phone_number', $user->phone_number);
            Session::put('status', $user->status);
            Session::put('role_name', $user->role_name);
            Session::put('avatar', $user->avatar);
            Session::put('position', $user->position);
            Session::put('department', $user->department);

            $activityLog = ['name' => Session::get('name'), 'email' => $username, 'description' => 'Has log in', 'date_time' => $todayDate];
            DB::table('activity_logs')->insert($activityLog);
            return redirect()->route('home')->with('success', 'Login successfully');
        }else {
            return redirect()->route('login')->with('error', 'WRONG USERNAME OR PASSWORD');
        }
    }

    public function logout(Request $request)
    {
        $dt = Carbon::now();
        $todayDate = $dt->toDayDateTimeString();
        $activityLog = ['name' => Session::get('name'), 'email' => Session::get('email'), 'description' => 'Has log out', 'date_time' => $todayDate];
        DB::table('activity_logs')->insert($activityLog);
        $request->session()->forget('name');
        $request->session()->forget('email');
        $request->session()->forget('rec_id');
        $request->session()->forget('join_date');
        $request->session()->forget('phone_number');
        $request->session()->forget('status');
        $request->session()->forget('role_name');
        $request->session()->forget('avatar');
        $request->session()->forget('position');
        $request->session()->forget('department');
        Session::flush();
        Auth::logout();
        return redirect('login');
    }
}
