<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\API\CheckDataEmployeeController;
use App\Http\Controllers\API\ForgotPasswordController;
use App\Http\Controllers\LoginController;
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
    Route::get('/logout', [ LoginController::class, 'logout'])->name('web.logout');

});

Route::get('routes', function () {
    $routeCollection = Route::getRoutes();

    echo "<table style='width:100%'>";
    echo "<tr>";
    echo "<td width='10%'><h4>HTTP Method</h4></td>";
    echo "<td width='10%'><h4>Route</h4></td>";
    echo "<td width='10%'><h4>Name</h4></td>";
    echo "<td width='70%'><h4>Corresponding Action</h4></td>";
    echo "</tr>";
    foreach ($routeCollection as $value) {
        echo "<tr>";
        echo "<td>" . $value->methods()[0] . "</td>";
        echo "<td>" . $value->uri() . "</td>";
        echo "<td>" . $value->getName() . "</td>";
        echo "<td>" . $value->getActionName() . "</td>";
        echo "</tr>";
    }
    echo "</table>";
})->name('routes');