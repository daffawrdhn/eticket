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

            $sla = [];
            foreach($isTicket as $ticket){
                $isStatusTicket = TicketStatusHistory::where('ticket_id', $ticket->ticket_id)->get();
                $data = [];
                foreach($isStatusTicket as $status){
                    $data[] = $status->status_after[0];
                }


                $sla[] = [
                    'ticket_id' => $ticket->ticket_id,
                    'time' => $data
                ];
            }

            return $this->sendResponse($sla, 'success');
           
        } catch (Exception $error) {
            return $this->sendError('Error Exception', ['error' => $error]);
        }
    }
}

