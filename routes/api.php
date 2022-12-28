<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CheckDataEmployeeController;
use App\Http\Controllers\API\ForgotPasswordController;
use App\Http\Controllers\FeatureController;

Route::post('login', [AuthController::class, 'signin'])->name('auth.login');
Route::post('register', [AuthController::class, 'register'])->name('auth.register');

Route::post('check-data', [CheckDataEmployeeController::class, 'checkEmployee'])->name('auth.checkdata');


Route::middleware('auth:api')->group( function () {

    Route::post('forgot-password', [ForgotPasswordController::class, 'forgotPassword'])->name('auth.forgotpassword');
    
    
    //feature
    Route::post('add-feature', [FeatureController::class, 'store'])->name('auth.addFeature');
    Route::post('update-feature/{id}', [FeatureController::class, 'update'])->name('auth.updateFeature');
    Route::delete('delete-feature/{id}', [FeatureController::class, 'destroy'])->name('auth.deleteFeature');
    Route::delete('delete-all-feature', [FeatureController::class, 'destroyAll'])->name('auth.deleteAllFeature');
    Route::get('get-feature', [FeatureController::class, 'getFeature'])->name('auth.getFeature');
    Route::get('get-feature/{id}', [FeatureController::class, 'show'])->name('auth.getFeatureById');

    
    Route::get('data', [AuthController::class, 'data'])->name('auth.data');

});