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
                $data = [];
                foreach($isStatusTicket as $status){

                    if ($status->status_after == 1) {
                        $submited = date('d F Y', strtotime($status->created_at));
                    }else{
                        $submited = 0;
                    }
                    
                    if($status->status_after == 2){
                        $approval1 = date('d F Y', strtotime($status->created_at));
                    }else{
                        $approval1 = 0;
                    }
                    
                    if($status->status_after == 3){
                        $approval2 = date('d F Y', strtotime($status->created_at));
                    }else{
                        $approval2 = 0;
                    }
                    
                    if($status->status_after == 4){
                        $approval3 = date('d F Y', strtotime($status->created_at));
                    }else{
                        $approval3 = 0;
                    }
                    
                    if($status->status_after == 5){
                        $finalApprov = date('d F Y', strtotime($status->created_at));
                    }else{
                        $finalApprov = 0;
                    }
                    
                    if($status->status_after == 6){
                        $reject = date('d F Y', strtotime($status->created_at));
                    }else{
                        $reject = 0;
                    }
                    
                    if($status->status_after == 7){
                        $onProcess = date('d F Y', strtotime($status->created_at));
                    }else{
                        $onProcess = 0;
                    }
                    
                    $data[] = [
                        'submited' => $submited,
                        'approval1' => $approval1,
                        'approval2' => $approval2,
                        'approval3' => $approval3,
                        'finalApprov' => $finalApprov,
                        'onProcess' => $onProcess,
                    ];
                
                }

                $datas[] = [
                    'ticket_id' => $ticket->ticket_id,
                    'status' => $data
                ];
            }

            return $this->sendResponse($datas, 'success');
           
        } catch (Exception $error) {
            return $this->sendError('Error Exception', ['error' => $error]);
        }
    }
}

