<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CheckDataEmployeeController;
use App\Http\Controllers\API\ForgotPasswordController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\RegionalController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SubFeatureController;
use App\Http\Controllers\TicketController;

Route::post('login', [AuthController::class, 'signin'])->name('auth.login');
Route::post('register', [AuthController::class, 'register'])->name('auth.register');

Route::post('check-data', [CheckDataEmployeeController::class, 'checkEmployee'])->name('auth.checkdata');


Route::middleware('auth:api')->group( function () {

    Route::post('forgot-password', [ForgotPasswordController::class, 'forgotPassword'])->name('auth.forgotpassword');
    
    //organization
    Route::post('add-organization', [OrganizationController::class, 'store'])->name('auth.addOrganization');
    Route::post('update-organization/{id}', [OrganizationController::class, 'update'])->name('auth.updateOrganization');
    Route::delete('delete-organization/{id}', [OrganizationController::class, 'destroy'])->name('auth.deleteOrganization');
    Route::delete('delete-all-organization', [OrganizationController::class, 'destroyAll'])->name('auth.deleteAllOrganization');
    Route::get('get-organization', [OrganizationController::class, 'getOrganization'])->name('auth.getOrganization');
    Route::get('get-organization/{id}', [OrganizationController::class, 'show'])->name('auth.getOrganizationById');

    //regional
    Route::post('add-regional', [RegionalController::class, 'store'])->name('auth.addRegional');
    Route::post('update-regional/{id}', [RegionalController::class, 'update'])->name('auth.updateRegional');
    Route::delete('delete-regional/{id}', [RegionalController::class, 'destroy'])->name('auth.deleteRegional');
    Route::delete('delete-all-regional', [RegionalController::class, 'destroyAll'])->name('auth.deleteAllRegional');
    Route::get('get-regional', [RegionalController::class, 'getRegional'])->name('auth.getRegional');
    Route::get('get-regional/{id}', [RegionalController::class, 'show'])->name('auth.getRegionalById');

    //role
    Route::post('add-role', [RoleController::class, 'store'])->name('auth.addRole');
    Route::post('update-role/{id}', [RoleController::class, 'update'])->name('auth.updateRole');
    Route::delete('delete-role/{id}', [RoleController::class, 'destroy'])->name('auth.deleteRole');
    Route::delete('delete-all-role', [RoleController::class, 'destroyAll'])->name('auth.deleteAllRole');
    Route::get('get-role', [RoleController::class, 'getRole'])->name('auth.getRole');
    Route::get('get-role/{id}', [RoleController::class, 'show'])->name('auth.getRoleById');
    
    //feature
    Route::post('add-feature', [FeatureController::class, 'store'])->name('auth.addFeature');
    Route::post('update-feature/{id}', [FeatureController::class, 'update'])->name('auth.updateFeature');
    Route::delete('delete-feature/{id}', [FeatureController::class, 'destroy'])->name('auth.deleteFeature');
    Route::delete('delete-all-feature', [FeatureController::class, 'destroyAll'])->name('auth.deleteAllFeature');
    Route::get('get-feature', [FeatureController::class, 'getFeature'])->name('auth.getFeature');
    Route::get('get-feature/{id}', [FeatureController::class, 'show'])->name('auth.getFeatureById');
    Route::post('select-feature', [FeatureController::class, 'selectFeature'])->name('auth.selectFeature');


    //sub feature
    Route::post('add-sub-feature', [SubFeatureController::class, 'store'])->name('auth.addSubFeature');
    Route::post('update-sub-feature/{id}', [SubFeatureController::class, 'update'])->name('auth.updateSubFeature');
    Route::delete('delete-sub-feature/{id}', [SubFeatureController::class, 'destroy'])->name('auth.deleteSubFeature');
    Route::delete('delete-all-sub-feature', [SubFeatureController::class, 'destroyAll'])->name('auth.deleteAllSubFeature');
    Route::get('get-sub-feature', [SubFeatureController::class, 'getSubFeature'])->name('auth.getSubFeature');
    Route::get('get-sub-feature/{id}', [SubFeatureController::class, 'show'])->name('auth.getSubFeatureById');

    //Ticket
    Route::post('add-ticket', [TicketController::class, 'store'])->name('auth.addTicket');
    Route::post('update-ticket/{id}', [TicketController::class, 'update'])->name('auth.updateTicket');
    Route::delete('delete-ticket/{id}', [TicketController::class, 'destroy'])->name('auth.deleteTicket');

    Route::get('get-ticket', [TicketController::class, 'getTicket'])->name('auth.getTicket');
    Route::get('get-approval', [TicketController::class, 'getApproval'])->name('auth.getApproval');

    Route::get('get-ticket/{id}', [TicketController::class, 'show'])->name('auth.getTicketById');
    Route::get('get-features', [TicketController::class, 'features'])->name('auth.getFeatures');

    Route::patch('update-ticket/status/{ticketId}', [TicketController::class, 'updateStatus'])->name('auth.updateStatus');
    Route::get('get-photo/{ticketId}', [TicketController::class, 'getPhoto'])->name('auth.getPhoto');


    // User
    Route::post('add-user', [EmployeeController::class, 'store'])->name('auth.addUser');
    Route::get('get-user', [EmployeeController::class, 'getEmployee'])->name('auth.getUser');
    Route::get('get-user/{id}', [EmployeeController::class, 'show'])->name('auth.getUserById');
    Route::post('update-user/{id}', [EmployeeController::class, 'update'])->name('auth.updateUser');
    Route::post('reset-user-password/{id}', [EmployeeController::class, 'resetPassword'])->name('auth.resetPassword');
    Route::delete('delete-user/{id}', [EmployeeController::class, 'destroy'])->name('auth.deleteUser');
    Route::delete('delete-all-user', [EmployeeController::class, 'destroyAll'])->name('auth.deleteAllUser');
    Route::post('set-status-employee/{id}', [EmployeeController::class, 'setStatusEmployee'])->name('auth.setStatusEmployee');
    
    

    // select data
    Route::post('select-user', [EmployeeController::class, 'selectEmployee'])->name('auth.selectUser');
    Route::post('select-organization', [OrganizationController::class, 'selectOrganization'])->name('auth.selectOrganization');
    Route::post('select-role', [RoleController::class, 'selectRole'])->name('auth.selectRolel');
    Route::post('select-regional', [RegionalController::class, 'selectRegional'])->name('auth.selectRegional');

    Route::get('data', [AuthController::class, 'data'])->name('auth.data');

});