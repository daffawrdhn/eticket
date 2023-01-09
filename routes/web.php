<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\API\CheckDataEmployeeController;
use App\Http\Controllers\API\ForgotPasswordController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\RegionalController;
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
    Route::get('/setting-user', [EmployeeController::class, 'index']);
    Route::get('/change-password', [PasswordController::class, 'index']);
    Route::get('/setting-role', [RoleController::class, 'index']);
    Route::get('/setting-organization', [OrganizationController::class, 'index']);
    Route::get('/setting-regional', [RegionalController::class, 'index']);
    Route::get('/setting-feature', [FeatureController::class, 'index']);
    Route::get('/setting-sub-feature', [SubFeatureController::class, 'index']);
    Route::get('/logout', [ LoginController::class, 'logout'])->name('web.logout');

});

Route::get('/storage/{path}', function ($path) {
    // Check if the user is authenticated
    if (!auth()->check()) {
        // Redirect the user to the login page
        return redirect()->route('login');
    } else {
        $path = storage_path($path);
        if (!File::exists($path)) {
            abort(404);
        } else {
            $file = File::get($path);
            $type = File::mimeType($path);
            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);
            return $response;
        }   
    }
})->where('path', '.*');
