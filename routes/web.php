<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// ----------------------------- register ---------------------------------------//
Route::controller(\App\Http\Controllers\Auth\RegisterController::class)->group(function() {
    Route::get('register', 'register')->name('register');
    Route::post('register', 'storeUser')->name('register');
});

// ----------------------------- login ---------------------------------------//
Route::controller(\App\Http\Controllers\Auth\LoginController::class)->group(function() {
    Route::get('login', 'login')->name('login');
    Route::post('login', 'authenticate');
    Route::get('logout', 'logout')->name('logout');
});

// ----------------------------- main dashboard ------------------------------//
Route::controller(HomeController::class)->group(function() {
    Route::get('/', 'index')->name('home');
});

// ----------------------------- form employee ------------------------------//
Route::controller(\App\Http\Controllers\EmployeeController::class)->group(function() {
    Route::get('form/departments/page', 'index')->name('form/departments/page');
    Route::post('form/departments/save', 'saveRecordDepartment')->name('form/departments/save');
    Route::post('form/departments/update', 'updateRecordDepartment')->name('form/departments/update');
    Route::post('form/departments/delete', 'deleteRecordDepartment')->name('form/departments/delete');
    Route::get('all/employee/card', 'cardAllEmployee')->name('all/employee/card');
    Route::post('all/employee/save', 'saveRecord')->name('all/employee/save');
    Route::get('all/employee/list', 'listAllEmployee')->name('all/employee/list');
});

// -----------------------------settings----------------------------------------//
Route::controller(\App\Http\Controllers\SettingController::class)->group(function () {
    Route::get('company/settings/page', 'companySettings')->name('company/settings/page');
    Route::get('roles/permissions/page', 'rolesPermissions')->name('roles/permissions/page');
    Route::post('roles/permissions/save', 'addRecord')->name('roles/permissions/save');
    Route::post('roles/permissions/update', 'editRolesPermissions')->name('roles/permissions/update');
    Route::post('roles/permissions/delete', 'deleteRolesPermissions')->name('roles/permissions/delete');
});

// ----------------------------- user userManagement -----------------------//
Route::controller(\App\Http\Controllers\UserManagementController::class)->group(function () {
    Route::get('userManagement', 'index')->middleware('auth')->name('userManagement');
    Route::post('user/add/save', 'addNewUserSave')->name('user/add/save');
    Route::post('search/user/list', 'searchUser')->name('search/user/list');
    Route::post('update', 'update')->name('update');
    Route::post('user/delete', 'delete')->middleware('auth')->name('user/delete');
    Route::get('activity/log', 'activityLog')->middleware('auth')->name('activity/log');
    Route::get('activity/login/logout', 'activityLogInLogOut')->middleware('auth')->name('activity/login/logout');
});

// Auth::routes();

