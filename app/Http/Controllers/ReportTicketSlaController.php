<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Models\Employee;
use App\Models\Ticket;
use App\Models\TicketStatusHistory;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ReportTicketSlaController extends BaseController
{
    public function index()
    {
        return view('containers.reports.report_sla');
    }

    public function getDataSLA(Request $request){
        try {

            if ($request->regionalId == 0) {
                
                $isTicket = Ticket::orderBy('created_at', 'DESC')
                            ->whereIn('ticket_status_id', [6,8])
                            ->get();
            }else{
                $isEmployees = Employee::select('employee_id')->where('regional_id', $request->regionalId)->get();

                $isTicket = Ticket::orderBy('created_at', 'DESC')
                            ->whereIn('employee_id', $isEmployees)
                            ->whereNotIn('ticket_status_id', [1,2,3,4,5,7])
                            ->get();
            }


            $datas = [];
            foreach($isTicket as $ticket){
                $isStatusTicket = TicketStatusHistory::where('ticket_id', $ticket->ticket_id)->first();
                $isEmployee = Employee::with('regional')->where('employee_id', $ticket->employee_id)->first();

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
                        $isSla = 'in process';
                    }
                }


                $datas[] = [
                    'ticket_id' => $ticket->ticket_id,
                    'employee_id' => $ticket->employee_id,
                    'regional_name' => $isEmployee->regional->regional_name,
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

            return $this->sendResponse($datas, 'success');

            if ($request->ajax()) {
                $costumers = $datas;
                return DataTables::of($costumers)->toJson();
            }
           
        } catch (Exception $error) {
            return $this->sendError('Error Exception', ['error' => $error]);
        }
    }


    public function dateInterval($startDate, $endDate)
    {

        $diffInDays = $startDate->diffInDaysFiltered(function (Carbon $date) {
            return $date->isWeekday();
        }, $endDate, true);
        
        // Menghitung selisih waktu
        $diffInHours = $startDate->diffInHours($endDate) % 24;
        
        return $diffInDays . " hari " . $diffInHours . " jam ";
    }
}

