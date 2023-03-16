<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('todo-list', [\App\Http\Controllers\TodoListController::class, 'index'])->name('todo-list.index');

Route::get('todo-list/{list}', [\App\Http\Controllers\TodoListController::class, 'show'])->name('todo-list.show');

Route::post('todo-list', [\App\Http\Controllers\TodoListController::class, 'store'])->name('todo-list.store');

Route::patch('todo-list/{list}', [\App\Http\Controllers\TodoListController::class, 'update'])->name('todo-list.update');

Route::delete('todo-list/{list}', [\App\Http\Controllers\TodoListController::class, 'destroy'])->name('todo-list.destroy');
