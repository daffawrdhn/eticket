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
                $isStatusTicket = TicketStatusHistory::whereIn($ticket->ticket_id)->get();

                $datas[] = $isStatusTicket; 
            }

            return $this->sendResponse($datas, 'success');
           
        } catch (Exception $error) {
            return $this->sendError('Error Exception', ['error' => $error]);
        }
    }
}

