<?php 
namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController as BaseController;

class ReportSummaryController extends BaseController
{
    public function index()
    {
        return view('containers.reports.report_summary');
    }
}



?>