<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Models\Ticket;
use App\Models\TicketStatusHistory;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
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
                $isStatusTicket = TicketStatusHistory::where('ticket_id', $ticket->ticket_id)->first();

                $submited = TicketStatusHistory::where('ticket_id', $isStatusTicket->ticket_id)->where('status_after', 1)->first();
                $approve1 = TicketStatusHistory::where('ticket_id', $isStatusTicket->ticket_id)->where('status_after', 2)->first();
                $approve2 = TicketStatusHistory::where('ticket_id', $isStatusTicket->ticket_id)->where('status_after', 3)->first();
                $approve3 = TicketStatusHistory::where('ticket_id', $isStatusTicket->ticket_id)->where('status_after', 4)->first();
                $finalApprove = TicketStatusHistory::where('ticket_id', $isStatusTicket->ticket_id)->where('status_after', 5)->first();
                $reject = TicketStatusHistory::where('ticket_id', $isStatusTicket->ticket_id)->where('status_after', 6)->first();
                $inProgress = TicketStatusHistory::where('ticket_id', $isStatusTicket->ticket_id)->where('status_after', 7)->first();
                $done = TicketStatusHistory::where('ticket_id', $isStatusTicket->ticket_id)->where('status_after', 8)->first();


                $isSubmited = $submited == null ? '-' : date('d F Y', strtotime($submited->created_at));
                $isApprove1 = $approve1 == null ? '-' : date('d F Y', strtotime($approve1->created_at));
                $isApprove2 = $approve2 == null ? '-' : date('d F Y', strtotime($approve2->created_at));
                $isApprove3 = $approve3 == null ? '-' : date('d F Y', strtotime($approve3->created_at));
                $isFinal = $finalApprove == null ? '-' : date('d F Y', strtotime($finalApprove->created_at));
                $isReject = $reject == null ? '-' : date('d F Y', strtotime($reject->created_at));
                $isInProgress = $inProgress == null ? '-' : date('d F Y', strtotime($inProgress->created_at));
                $isDone = $done == null ? '-' : date('d F Y', strtotime($done->created_at));


                if ($done != null) {
                    $isSla = $this->dateInterval($submited->created_at, $done->created_at);
                }else{
                    if ($reject != null) {
                        $isSla = $this->dateInterval($submited->created_at, $reject->created_at);
                    }else{
                        $isSla = '-';
                    }
                }


                $sla[] = [
                    'ticket_id' => $ticket->ticket_id,
                    'submited_date' => $isSubmited,
                    'approve1_date' => $isApprove1,
                    'approve2_date' => $isApprove2,
                    'approve3_date' => $isApprove3,
                    'final_approve_date' => $isFinal,
                    'reject_date' => $isReject,
                    'in_progress' => $isInProgress,
                    'is_done' => $isDone,
                    'sla_total' => $isSla
                ];
            }

            return $this->sendResponse($sla, 'success');
           
        } catch (Exception $error) {
            return $this->sendError('Error Exception', ['error' => $error]);
        }
    }


    public function dateInterval($startDate, $endDate)
    {

        $diff = $startDate->diffInHoursFiltered(function (Carbon $date) {
            return $date->isWeekday() && $date->between('9:00', '17:00');
        }, $endDate);
        
        // hitung selisih waktu dalam format hari:jam:menit:detik
        $diff_formatted = Carbon::createFromTimestamp($diff * 3600)->diffForHumans(['parts' => 4]);
        
        // output selisih waktu
        return $diff_formatted;
    }
}

