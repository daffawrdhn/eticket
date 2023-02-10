<?php

namespace App\Http\Controllers;

use App\Exports\ExportEmployee;
use App\Exports\ExportSummary;
use App\Exports\ExportTicket;
use App\Http\Controllers\API\BaseController;
use App\Models\Regional;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends BaseController
{
    public function exportRegional(Request $request) 
    {
        $regionalId = $request->regionalId;
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        
        if ($regionalId == 0) {
            $fileName = "report eticket";

            if ($startDate != "") {
                $fileName = "report eticket (".$startDate." to ".$endDate.")";
            }else{
                $fileName = "report eticket";
            }
        }else{
            $regionalName = Regional::where('regional_id', $regionalId)->first();

            if ($startDate != "") {
                $fileName = "report eticket Regional ".$regionalName->regional_name."(".$startDate." to ".$endDate.")";
            }else{
                $fileName = "report eticket Regional ".$regionalName->regional_name;
            }
        }


        return Excel::download(new ExportTicket($regionalId, $startDate, $endDate), $fileName.'.xlsx');
    }


    public function exportSummary(Request $request){
        $regionalId = $request->regionalId;
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        
        if ($regionalId == 0) {

            if ($startDate != "") {
                $fileName = "report eticket summary (".$startDate." to ".$endDate.")";
            }else{
                $fileName = "report eticket summary";
            }
        }else{
            $regionalName = Regional::where('regional_id', $regionalId)->first();

            if ($startDate != "") {
                $fileName = "report eticket summary Regional ".$regionalName->regional_name."(".$startDate." to ".$endDate.")";
            }else{
                $fileName = "report eticket summary Regional ".$regionalName->regional_name;
            }
        }


        return Excel::download(new ExportSummary($regionalId, $startDate, $endDate), $fileName.'.xlsx');
    }


    public function exportEmployee(Request $request){

        if($request->regionalId == 0){
            $fileName = 'report employee';
        }else{
            $regionalName = Regional::where('regional_id', $request->regionalId)->first();
            $fileName = 'report employee by regional '.$regionalName->regional_name;
        }

        return Excel::download(new ExportEmployee($request->regionalId), $fileName.'.xlsx');
    }
     
}
