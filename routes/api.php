<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;


Route::post('login', [AuthController::class, 'signin'])->name('auth.login');
Route::post('register', [AuthController::class, 'register'])->name('auth.register');

Route::post('checkdata', [AuthController::class, 'checkData'])->name('auth.checkdata');


Route::middleware('auth:api')->group( function () {

    Route::post('forgotpassword', [AuthController::class, 'forgotPassword'])->name('auth.forgotpassword');
    Route::get('data', [AuthController::class, 'data'])->name('auth.data');

});