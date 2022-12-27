<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\API\CheckDataEmployeeController;
use App\Http\Controllers\API\ForgotPasswordController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [LoginController::class, 'index'])->name('login');
Route::get('/check-employee', [CheckDataEmployeeController::class, 'index']);
Route::get('/forgot-password/{token}', [ForgotPasswordController::class, 'index']);

Route::middleware('auth:sanctum')->group( function () {


    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/change-password', [PasswordController::class, 'index']);
    Route::get('/setting-role', [RoleController::class, 'index']);
    Route::get('/logout', [ LoginController::class, 'logout'])->name('web.logout');

});
