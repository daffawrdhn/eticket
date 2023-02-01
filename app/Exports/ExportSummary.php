<?php

namespace App\Exports;

use App\Models\Employee;
use App\Models\Regional;
use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportSummary implements FromCollection, WithHeadings
{
    
    private $regionalId;
    private $startDate;
    private $endDate;

    public function __construct($regionalId, $startDate, $endDate)
    {

        $this->regionalId = $regionalId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        
    }


    public function collection()
    {
        if ($this->regionalId == 0) {
            if ($this->startDate != "") {
                $regionals = Regional::all();

                $datas = [];
                $totalApprove = 0;
                $totalOpen = 0;
                $totalReject = 0;
                $totalTicket = 0;
                foreach ($regionals as $regional) {
                    $employees = Employee::select('employee_id')->where('regional_id', $regional->regional_id)->get();
                    $total_ticket = Ticket::whereIn('employee_id', $employees)
                                    ->whereBetween('created_at', [$this->startDate, $this->endDate])
                                    ->selectRaw('COUNT(*) as total_ticket')
                                    ->first();

                    $tickets = Ticket::whereIn('employee_id', $employees)
                    ->whereBetween('created_at', [$this->startDate, $this->endDate])
                    ->get();


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

                    $datas[] = [
                        'regional_name' => $regional->regional_name, 
                        'open' => $open.' tickets',
                        'approve' => $approve.' tickets',
                        'reject' => $reject.' tickets',
                        'total_ticket' => $total_ticket->total_ticket.' tickets'
                    ];
                    
                }
            }else{

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

                    $tickets = Ticket::whereIn('employee_id', $employees)->get();
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

                    $datas[] = [
                        'regional_name' => $regional->regional_name, 
                        'open' => $open.' tickets',
                        'approve' => $approve.' tickets',
                        'reject' => $reject.' tickets',
                        'total_ticket' => $total_ticket->total_ticket.' tickets'
                    ];
                    
                }
            }

        }else{
            if ($this->startDate != "") {

                $regionals = Regional::where('regional_id', $this->regionalId)->get();

                $datas = [];
                $totalApprove = 0;
                $totalOpen = 0;
                $totalReject = 0;
                $totalTicket = 0;
                foreach ($regionals as $regional) {
                    $employees = Employee::select('employee_id')->where('regional_id', $regional->regional_id)->get();
                    $total_ticket = Ticket::whereIn('employee_id', $employees)
                                    ->whereBetween('created_at', [$this->startDate, $this->endDate])
                                    ->selectRaw('COUNT(*) as total_ticket')
                                    ->first();

                    $tickets = Ticket::whereIn('employee_id', $employees)
                    ->whereBetween('created_at', [$this->startDate, $this->endDate])
                    ->get();


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

                    $datas[] = [
                        'regional_name' => $regional->regional_name, 
                        'open' => $open.' tickets',
                        'approve' => $approve.' tickets',
                        'reject' => $reject.' tickets',
                        'total_ticket' => $total_ticket->total_ticket.' tickets'
                    ];
                    
                }
            }else{

                $regionals = Regional::where('regional_id', $this->regionalId)->get();

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

                    $tickets = Ticket::whereIn('employee_id', $employees)->get();
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

                    $datas[] = [
                        'regional_name' => $regional->regional_name, 
                        'open' => $open.' tickets',
                        'approve' => $approve.' tickets',
                        'reject' => $reject.' tickets',
                        'total_ticket' => $total_ticket->total_ticket.' tickets'
                    ];
                    
                }
            }
            

            // return $this->sendResponse($datas, 'success');
        }

        $datas[] = [
            'regional_name' => 'Total Jumlah',
            'approve' => $totalApprove.' tickets',
            'reject' => $totalReject.' tickets',
            'open' => $totalOpen.' tickets',
            'total_ticket' => $totalTicket.' tickets'
        ];


        return collect($datas);
    }

    public function headings(): array
    {
        return ["Regional", "Ticket Proccess", "Ticket Approval", "Ticket Reject", "Total Ticket"];
    }
}
