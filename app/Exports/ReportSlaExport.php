<?php

namespace App\Exports;

use App\Models\Employee;
use App\Models\Ticket;
use App\Models\TicketStatusHistory;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportSlaExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    private $regionalId;

    public function __construct($regionalId)
    {

        $this->regionalId = $regionalId;
    }
    public function collection()
    {
        if ($this->regionalId == 0) {
                
            $isTicket = Ticket::orderBy('created_at', 'DESC')
                        ->whereIn('ticket_status_id', [4,5,7,8])
                        ->get();
        }else{
            $isEmployees = Employee::select('employee_id')->where('regional_id', $this->regionalId)->get();

            $isTicket = Ticket::orderBy('created_at', 'DESC')
                        ->whereIn('employee_id', $isEmployees)
                        ->whereNotIn('ticket_status_id', [1,2,3,6])
                        ->get();
        }


        $datas = [];
        foreach($isTicket as $ticket){
            $isStatusTicket = TicketStatusHistory::where('ticket_id', $ticket->ticket_id)
                                ->where('status_before', 4)
                                ->first();
            $isEmployee = Employee::with('regional')->where('employee_id', $ticket->employee_id)->first();

            if ($isStatusTicket != null) {
                $submited = TicketStatusHistory::where('ticket_id', $isStatusTicket->ticket_id)->where('status_after', 1)->first();
                $approve1 = TicketStatusHistory::where('ticket_id', $isStatusTicket->ticket_id)->where('status_after', 2)->first();
                $approve2 = TicketStatusHistory::where('ticket_id', $isStatusTicket->ticket_id)->where('status_after', 3)->first();
                $approve3 = TicketStatusHistory::where('ticket_id', $isStatusTicket->ticket_id)->where('status_after', 4)->first();
                $finalApprove = TicketStatusHistory::where('ticket_id', $isStatusTicket->ticket_id)->where('status_after', 5)->first();
                $inProgress = TicketStatusHistory::where('ticket_id', $isStatusTicket->ticket_id)->where('status_after', 7)->first();
                $done = TicketStatusHistory::where('ticket_id', $isStatusTicket->ticket_id)->where('status_after', 8)->first();
                
                
                $isSubmited = $submited == null ? '-' : date('d F Y', strtotime($submited->created_at));
                $isApprove1 = $approve1 == null ? '-' : date('d F Y', strtotime($approve1->created_at));
                $isApprove2 = $approve2 == null ? '-' : date('d F Y', strtotime($approve2->created_at));
                $isApprove3 = $approve3 == null ? '-' : date('d F Y', strtotime($approve3->created_at));
                $isFinal = $finalApprove == null ? '-' : date('d F Y', strtotime($finalApprove->created_at));
                $isInProgress = $inProgress == null ? '-' : date('d F Y', strtotime($inProgress->created_at));
                $isDone = $done == null ? '-' : date('d F Y', strtotime($done->created_at));


                if ($done != null) {
                    $isSla = $this->dateInterval($submited->created_at, $done->created_at);
                    $status = 'Done';
                }else{
                    if ($inProgress != null) {
                        $isSla = $this->dateInterval($submited->created_at, $inProgress->created_at);
                        $status = 'On Progress';
                    }else{
                        if ($finalApprove != null) {
                            $isSla = $this->dateInterval($submited->created_at, $finalApprove->created_at);
                            $status = 'In Progress';
                        }else{
                            $isSla = $this->dateInterval($submited->created_at, $approve3->created_at);
                            $status = 'In Process';
                        }
                    }
                }


                $datas[] = [
                    'ticket_id' => strval($ticket->ticket_id),
                    'employee_id' => $ticket->employee_id,
                    'regional_name' => $isEmployee->regional->regional_name,
                    'submited_date' => $isSubmited,
                    'approve1_date' => $isApprove1,
                    'approve2_date' => $isApprove2,
                    'approve3_date' => $isApprove3,
                    'final_approve_date' => $isFinal,
                    'in_progress' => $isInProgress,
                    'is_done' => $isDone,
                    'status' => $status,
                    'sla_total' => $isSla,
                ];
            }
        }


        return collect($datas);
    }

    public function dateInterval($startDate, $endDate)
    {

        $diffInDays = $startDate->diffInDaysFiltered(function (Carbon $date) {
            return $date->isWeekday();
        }, $endDate);
        
        $day = $diffInDays - 1;
        // Menghitung selisih waktu
        $diffInHours = $startDate->diffInHours($endDate) % 24;
        
        return $day . " hari " . $diffInHours . " jam ";
    }

    public function headings(): array
    {
        return ["Ticket Id", "NIK", "Regional Name", "Submited Date", "Approval 1 Date", "Approval 2 Date", "Approval 3 Date", 
        "Final Approve Date", "On Progress Date", "Done Date", "Status", "Sla Total"];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:M1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
            },

        ];
    }
}
