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
            $totalApprove = 0;
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
                $approve = 0;
                foreach($tickets as $ticket){
                    if ($ticket->ticket_status_id == 5) {
                        $approve += 1;
                    }else if ($ticket->ticket_status_id == 6) {
                        $reject += 1;
                    }else{
                        $open += 1;
                     } 
                }
                
                $totalApprove += $approve;
                $totalOpen += $open;
                $totalReject += $reject;
                $totalTicket += $total_ticket->total_ticket;
                
            }

            $datas['labels'] = ['Process', 'Reject', 'Approve'];
            $datas['data'] = [$totalOpen, $totalReject, $totalApprove];

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
