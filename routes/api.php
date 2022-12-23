<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\forgotpassword\CheckDataEmployeeController;
use App\Http\Controllers\forgotpassword\ForgotPasswordController;

Route::post('login', [AuthController::class, 'signin'])->name('auth.login');
Route::post('register', [AuthController::class, 'register'])->name('auth.register');

Route::post('check-data', [CheckDataEmployeeController::class, 'checkEmployee'])->name('auth.checkdata');


Route::middleware('auth:api')->group( function () {

    Route::post('forgot-password', [ForgotPasswordController::class, 'forgotPassword'])->name('auth.forgotpassword');
    Route::get('data', [AuthController::class, 'data'])->name('auth.data');

});