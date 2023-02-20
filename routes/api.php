<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CheckDataEmployeeController;
use App\Http\Controllers\API\ForgotPasswordController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\DeptheadController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\HelpdeskController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\RegionalController;
use App\Http\Controllers\RegionalPicController;
use App\Http\Controllers\ReportRegionalController;
use App\Http\Controllers\ReportSummaryController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SubFeatureController;
use App\Http\Controllers\TicketController;
use App\Models\RegionalPIC;

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
    Route::get('get-organization-datatable', [OrganizationController::class, 'dataTableOrganization'])->name('auth.dataTableOrganization');
    Route::get('get-organization', [OrganizationController::class, 'getOrganization'])->name('auth.getOrganization');
    Route::get('get-organization/{id}', [OrganizationController::class, 'show'])->name('auth.getOrganizationById');

    //regional
    Route::post('add-regional', [RegionalController::class, 'store'])->name('auth.addRegional');
    Route::post('update-regional/{id}', [RegionalController::class, 'update'])->name('auth.updateRegional');
    Route::delete('delete-regional/{id}', [RegionalController::class, 'destroy'])->name('auth.deleteRegional');
    Route::delete('delete-all-regional', [RegionalController::class, 'destroyAll'])->name('auth.deleteAllRegional');
    Route::get('get-regional-datatable', [RegionalController::class, 'dataTableRegional'])->name('auth.dataTableRegional');
    Route::get('get-regional', [RegionalController::class, 'getRegional'])->name('auth.getRegional');
    Route::get('get-regional/{id}', [RegionalController::class, 'show'])->name('auth.getRegionalById');

    //role
    Route::post('add-role', [RoleController::class, 'store'])->name('auth.addRole');
    Route::post('update-role/{id}', [RoleController::class, 'update'])->name('auth.updateRole');
    Route::delete('delete-role/{id}', [RoleController::class, 'destroy'])->name('auth.deleteRole');
    Route::delete('delete-all-role', [RoleController::class, 'destroyAll'])->name('auth.deleteAllRole');
    Route::get('get-role', [RoleController::class, 'getRole'])->name('auth.getRole');
    Route::get('get-role-datatable', [RoleController::class, 'dataTableRole'])->name('auth.dataTableRole');
    Route::get('get-role/{id}', [RoleController::class, 'show'])->name('auth.getRoleById');
    
    //feature
    Route::post('add-feature', [FeatureController::class, 'store'])->name('auth.addFeature');
    Route::post('update-feature/{id}', [FeatureController::class, 'update'])->name('auth.updateFeature');
    Route::delete('delete-feature/{id}', [FeatureController::class, 'destroy'])->name('auth.deleteFeature');
    Route::delete('delete-all-feature', [FeatureController::class, 'destroyAll'])->name('auth.deleteAllFeature');
    Route::get('get-feature', [FeatureController::class, 'getFeature'])->name('auth.getFeature');
    Route::get('get-feature/{id}', [FeatureController::class, 'show'])->name('auth.getFeatureById');
    Route::post('select-feature', [FeatureController::class, 'selectFeature'])->name('auth.selectFeature');
    Route::get('get-feature-table', [FeatureController::class, 'featureDataTable'])->name('auth.getFeatureDataTable');
    Route::get('get-features', [TicketController::class, 'features'])->name('auth.getFeatures');

    //sub feature
    Route::post('add-sub-feature', [SubFeatureController::class, 'store'])->name('auth.addSubFeature');
    Route::post('update-sub-feature/{id}', [SubFeatureController::class, 'update'])->name('auth.updateSubFeature');
    Route::delete('delete-sub-feature/{id}', [SubFeatureController::class, 'destroy'])->name('auth.deleteSubFeature');
    Route::delete('delete-all-sub-feature', [SubFeatureController::class, 'destroyAll'])->name('auth.deleteAllSubFeature');
    Route::get('get-sub-feature', [SubFeatureController::class, 'getSubFeature'])->name('auth.getSubFeature');
    Route::get('get-sub-feature-table', [SubFeatureController::class, 'subFeatureDataTable'])->name('auth.getSubFeatureDataTable');
    Route::get('get-sub-feature/{id}', [SubFeatureController::class, 'show'])->name('auth.getSubFeatureById');

    //Ticket
    Route::post('add-ticket', [TicketController::class, 'store'])->name('auth.addTicket');
    Route::post('update-ticket/{id}', [TicketController::class, 'update'])->name('auth.updateTicket');
    Route::delete('delete-ticket/{id}', [TicketController::class, 'destroy'])->name('auth.deleteTicket');

    Route::get('get-ticket', [TicketController::class, 'getTicket'])->name('auth.getTicket');
    Route::get('get-approval', [TicketController::class, 'getApproval'])->name('auth.getApproval');
    Route::get('get-todo', [TicketController::class, 'getTodo'])->name('auth.getTodo');

    Route::get('get-ticket/{id}', [TicketController::class, 'show'])->name('auth.getTicketById');
    

    Route::get('get-history', [TicketController::class, 'getHistory'])->name('auth.getHistory');
    Route::get('get-todo-history', [TicketController::class, 'getTodoHistory'])->name('auth.getTodoHistory');

    Route::get('get-summary', [TicketController::class, 'getSummary'])->name('auth.getSummary');

    

    // mobile
    Route::patch('update-ticket/status/{ticketId}', [TicketController::class, 'updateStatus'])->name('auth.updateStatus');
    Route::get('get-photo/{ticketId}', [TicketController::class, 'getPhoto'])->name('auth.getPhoto');
    Route::get('get-pics/{regionalId}', [RegionalPicController::class, 'getPics'])->name('auth.getPics');
    Route::get('get-helpdesks/{regionalId}', [HelpdeskController::class, 'getHelpdesks'])->name('auth.getHelpdesks');
    Route::get('get-depthead', [DeptheadController::class, 'getDepthead'])->name('auth.getDepthead');



    // User
    Route::post('add-user', [EmployeeController::class, 'store'])->name('auth.addUser');
    Route::post('get-user', [EmployeeController::class, 'getEmployee'])->name('auth.getUser');
    Route::get('get-user/{id}', [EmployeeController::class, 'show'])->name('auth.getUserById');
    Route::put('update-user/{id}', [EmployeeController::class, 'update'])->name('auth.updateUser');
    Route::post('reset-user-password/{id}', [EmployeeController::class, 'resetPassword'])->name('auth.resetPassword');
    Route::delete('delete-user/{id}', [EmployeeController::class, 'destroy'])->name('auth.deleteUser');
    Route::delete('delete-all-user', [EmployeeController::class, 'destroyAll'])->name('auth.deleteAllUser');
    Route::post('set-status-employee/{id}', [EmployeeController::class, 'setStatusEmployee'])->name('auth.setStatusEmployee');
    
    //approval
    Route::get('get-regional-pic', [RegionalPicController::class, 'getDataRegionalPic'])->name('auth.getDataRegionalPic');
    Route::post('add-regional-pic', [RegionalPicController::class, 'store'])->name('auth.inputRegionalPic');
    Route::get('get-regional-pic/{id}', [RegionalPicController::class, 'show'])->name('auth.getRegionalPicById');
    Route::post('update-regional-pic/{id}', [RegionalPicController::class, 'update'])->name('auth.updateRegionalPic');
    Route::delete('delete-regional-pic/{id}', [RegionalPicController::class, 'destroy'])->name('auth.deletePic');

    // helpdesk
    Route::get('get-helpdesk', [HelpdeskController::class, 'getDataHelpdesk'])->name('auth.getDataHelpdesk');
    Route::post('add-helpdesk', [HelpdeskController::class, 'store'])->name('auth.inputHelpdesk');
    Route::get('get-helpdesk/{id}', [HelpdeskController::class, 'show'])->name('auth.getHelpdeskById');
    Route::post('update-helpdesk/{id}', [HelpdeskController::class, 'update'])->name('auth.updateHelpdesk');
    Route::delete('delete-helpdesk/{id}', [HelpdeskController::class, 'destroy'])->name('auth.deleteHelpdesk');

    //DeptHead
    Route::get('get-data-depthead', [DeptheadController::class, 'getDataDepthead'])->name('auth.getDepthead');
    Route::post('add-depthead', [DeptheadController::class, 'store'])->name('auth.inputDepthead');
    Route::get('get-depthead/{id}', [DeptheadController::class, 'show'])->name('auth.getDeptheadById');
    Route::post('update-depthead/{id}', [DeptheadController::class, 'update'])->name('auth.updateDepthead');
    Route::delete('delete-depthead/{id}', [DeptheadController::class, 'destroy'])->name('auth.deleteDepthead');

    // select data
    Route::post('select-user', [EmployeeController::class, 'selectEmployee'])->name('auth.selectUser');
    Route::post('select-organization', [OrganizationController::class, 'selectOrganization'])->name('auth.selectOrganization');
    Route::post('select-role', [RoleController::class, 'selectRole'])->name('auth.selectRolel');
    Route::post('select-regional', [RegionalController::class, 'selectRegional'])->name('auth.selectRegional');
    Route::post('select-regional/{id}', [RegionalController::class, 'selectRegionalByEmployeeId'])->name('auth.selectRegionalByEmployee');
    Route::post('select-employee/{id}', [EmployeeController::class, 'selectEmployeeByRegional'])->name('auth.selectEmployeeByRegional');


    // ////report
    Route::post('get-report-regional', [ReportRegionalController::class, 'getReport'])->name('auth.getReport');
    Route::post('get-report-summary', [ReportSummaryController::class, 'getDataSumary'])->name('auth.getDataSumary');
    
    //chart
    Route::get('get-pie-chart', [ChartController::class,'pieChart'])->name('auth.pieChart');
    Route::get('get-bar-chart', [ChartController::class,'barChart'])->name('auth.barChart');
    Route::get('get-sumary-dashboard', [ReportSummaryController::class, 'sumaryDashboard'])->name('auth.dashboardSumary');

    Route::get('data', [AuthController::class, 'data'])->name('auth.data');


    // export Excel
    Route::post('export-report-regional', [ExportController::class, 'exportRegional']);
    Route::post('export-report-summary', [ExportController::class, 'exportSummary']);
    Route::post('export-report-employee', [ExportController::class, 'exportEmployee']);

    // here for goodness sake
});