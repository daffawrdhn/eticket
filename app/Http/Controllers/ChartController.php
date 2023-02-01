<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Models\Employee;
use App\Models\Regional;
use App\Models\Ticket;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use PHPUnit\Framework\Constraint\Count;

class ChartController extends BaseController
{
    public function pieChart(){
        try {
            $regionals = Regional::all();
           
            $datas = [];
            $totalApproved = 0;
            $totalApprove1 = 0;
            $totalApprove2 = 0;
            $totalApprove3 = 0;
            $totalOpen = 0;
            $totalReject = 0;
            $totalTicket = 0;
            foreach ($regionals as $regional) {
                $employees = Employee::select('employee_id')->where('regional_id', $regional->regional_id)->get();
                $total_ticket = Ticket::whereIn('employee_id', $employees)
                                ->selectRaw('COUNT(*) as total_ticket')
                                ->first();

                $tickets = Ticket::whereDate('created_at', Carbon::today())->whereIn('employee_id', $employees)->get();
                $open = 0;
                $reject = 0;
                $approved = 0;
                $approve1 = 0;
                $approve2 = 0;
                $approve3 = 0;
                foreach($tickets as $ticket){
                    if ($ticket->ticket_status_id == 5) {
                        $approved += 1;
                    }else if ($ticket->ticket_status_id == 6) {
                        $reject += 1;
                    }else if ($ticket->ticket_status_id == 1) {
                        $open += 1;
                    }else if($ticket->ticket_status_id == 2){
                        $approve1 += 1;
                    }else if($ticket->ticket_status_id == 3){
                        $approve2 += 1;
                    }else if($ticket->ticket_status_id == 4){
                        $approve3 += 1;
                    }  
                }
                
                $totalApproved += $approved;
                $totalApprove1 += $approve1;
                $totalApprove2 += $approve2;
                $totalApprove3 += $approve3;
                $totalOpen += $open;
                $totalReject += $reject;
                $totalTicket += $total_ticket->total_ticket;
                
            }

            $datas['labels'] = ['Open', 'Reject', 'Approve 1', 'Approve 2', 'Approve 3', 'Approved'];
            $datas['data'] = [$totalOpen, $totalReject,$totalApprove1,$totalApprove2,$totalApprove3, $totalApproved];

            return $this->sendResponse($datas, 'success');

        } catch (Exception $error) {
            return $this->sendError('Error validation', ['error' => $error]);
        }
    }

    public function barChart(){
        try {
            $regionals = Regional::all();
           
            $datas = [];
            $regionalName = [];
            $regionalTicket = [];
            foreach ($regionals as $regional) {
                $employees = Employee::select('employee_id')->where('regional_id', $regional->regional_id)->get();

                $tickets = Count(Ticket::whereMonth('created_at', Carbon::now()->month)
                            ->whereYear('created_at', '=', Carbon::now()->year)
                            ->whereIn('employee_id', $employees)->get());
                
                $regionalName[] = $regional->regional_name;
                $regionalTicket[] = $tickets;

            }
            $datas['regional'] = $regionalName;
            $datas['total_ticket'] = $regionalTicket;
            $datas['yMax'] = Count(Ticket::whereMonth('created_at', Carbon::now()->month)
                                            ->whereYear('created_at', '=', Carbon::now()->year)->get());
            $datas['xMax'] = Count($regionals);


            return $this->sendResponse($datas, 'success');

        } catch (Exception $error) {
            return $this->sendError('Error validation', ['error' => $error]);
        }
    }
}
