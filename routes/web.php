<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\API\CheckDataEmployeeController;
use App\Http\Controllers\API\ForgotPasswordController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\HelpdeskController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\RegionalController;
use App\Http\Controllers\RegionalPicController;
use App\Http\Controllers\ReportRegionalController;
use App\Http\Controllers\ReportSummaryController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SubFeatureController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;

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
    Route::get('/setting-user', [EmployeeController::class, 'index'])->name('user.get');
    Route::get('/change-password', [PasswordController::class, 'index']);
    Route::get('/setting-role', [RoleController::class, 'index']);
    Route::get('/setting-organization', [OrganizationController::class, 'index']);
    Route::get('/setting-regional', [RegionalController::class, 'index']);
    Route::get('/setting-feature', [FeatureController::class, 'index']);
    Route::get('/setting-sub-feature', [SubFeatureController::class, 'index']);
    Route::get('/setting-regional-pic', [RegionalPicController::class, 'index']);
    Route::get('/setting-Helpdesk', [HelpdeskController::class, 'index']);
    Route::get('/report-data-sumary', [ReportSummaryController::class, 'index']);
    Route::get('/report-regional', [ReportRegionalController::class, 'index']);
    Route::get('/logout', [ LoginController::class, 'logout'])->name('web.logout');

});