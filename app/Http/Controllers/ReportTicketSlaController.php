<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Models\Ticket;
use App\Models\TicketStatusHistory;
use Exception;
use Illuminate\Http\Request;

class ReportTicketSlaController extends BaseController
{
    public function index()
    {
        return view('containers.reports.report_sla');
    }

    public function getDataSLA(Request $request){
        try {

            $isTicket = Ticket::all();

            
            $datas = [];
            foreach($isTicket as $ticket){
                $isStatusTicket = TicketStatusHistory::where('ticket_id', $ticket->ticket_id)->get();
                foreach($isStatusTicket as $status){
                    $isData['ticket_id'] = $status->ticket_id;
                    if ($status->status_after == 1) {
                        $submited = date('d F Y', strtotime($status->created_at));
                        $isData['submited'] = $submited; 
                    }else{
                        $submited = 0;
                        $isData['submited'] = $submited; 
                    }
                    
                    if($status->status_after == 2){
                        $approval1 = date('d F Y', strtotime($status->created_at));
                        $isData['approval1'] = $approval1; 
                    }else{
                        $approval1 = 0;
                        $isData['approval1'] = $approval1; 
                    }
                    
                    if($status->status_after == 3){
                        $approval2 = date('d F Y', strtotime($status->created_at));
                        $isData['approval2'] = $approval2;
                    }else{
                        $approval2 = 0;
                        $isData['approval2'] = $approval2;
                    }
                    
                    if($status->status_after == 4){
                        $approval3 = date('d F Y', strtotime($status->created_at));
                        $isData['approval3'] = $approval3;
                    }else{
                        $approval3 = 0;
                        $isData['approval3'] = $approval3;
                    }
                    
                    if($status->status_after == 5){
                        $finalApprov = date('d F Y', strtotime($status->created_at));
                        $isData['finalApprov'] = $finalApprov;
                    }else{
                        $finalApprov = 0;
                        $isData['finalApprov'] = $finalApprov;
                    }
                    
                    if($status->status_after == 6){
                        $reject = date('d F Y', strtotime($status->created_at));
                        $isData['reject'] = $reject;
                    }else{
                        $reject = 0;
                        $isData['reject'] = $reject;
                    }
                    
                    if($status->status_after == 7){
                        $onProcess = date('d F Y', strtotime($status->created_at));
                        $isData['onProcess'] = $onProcess;
                    }else{
                        $onProcess = 0;
                        $isData['onProcess'] = $onProcess;
                    }
                
                }

                $datas[] = $isData;
            }

            return $this->sendResponse($datas, 'success');
           
        } catch (Exception $error) {
            return $this->sendError('Error Exception', ['error' => $error]);
        }
    }
}

