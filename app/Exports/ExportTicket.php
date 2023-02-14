<?php

namespace App\Exports;

use App\Models\Employee;
use App\Models\Regional;
use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\FromCollection as FromCollection;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportTicket implements FromCollection, WithHeadings, ShouldAutoSize
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
                
                $tickets = Ticket::orderBy('created_at', 'desc')->whereBetween('created_at', [$this->startDate, $this->endDate])        
                ->with('feature', 'subFeature', 'ticketStatus')    
                ->get();
                
            }else{
                
                $tickets = Ticket::orderBy('created_at', 'desc')->with('feature', 'subFeature', 'ticketStatus')->get();
            }
        }else{

            if ($this->startDate != "") {
                $employees = Employee::where('regional_id', $this->regionalId)->get();
                
                $tickets = [];
                foreach($employees as $employee):
                    $ticket = Ticket::orderBy('created_at', 'desc')->where('employee_id', $employee->employee_id)
                        ->whereBetween('created_at', [$this->startDate, $this->endDate])        
                        ->with('feature', 'subFeature', 'ticketStatus')    
                        ->get();

                    foreach ($ticket as $t) {
                        $tickets[] = $t;
                    }
                endforeach;
            }else{
                $employees = Employee::where('regional_id', $this->regionalId)->get();
                
                $tickets = [];
                foreach($employees as $employee):
                    $ticket = Ticket::orderBy('created_at', 'desc')->where('employee_id', $employee->employee_id)
                        ->with('feature', 'subFeature', 'ticketStatus')            
                        ->get();

                    foreach ($ticket as $t) {
                        $tickets[] = $t;
                    }
                endforeach;
            }
        }


        $datas= [];
        $no = 1;
        foreach($tickets as $t){
            $getEmployee = Employee::with('regional')->where('employee_id', $t->employee_id)->first();
            $getSupervisor = Employee::where('employee_id', $t->supervisor_id)->first();

            $data['no'] = $no;
            $data['employee_id'] = $t->employee_id;
            $data['employee_name'] = $getEmployee->employee_name;
            $data['supervisor_id'] = $t->supervisor_id;
            $data['supervisor_name'] = $getSupervisor->employee_name;
            $data['regional'] = $getEmployee->regional->regional_name;
            $data['jenis_ticket'] = $t->feature['feature_name'];
            $data['sub_feature'] = $t->subFeature->sub_feature_name;
            $data['ticket_title'] = $t->ticket_title;
            $data['ticket_description'] = $t->ticket_description;
            $data['ticket_status'] = $t->ticketStatus['ticket_status_name'];
            $data['date'] = date('d-m-Y', strtotime($t->created_at));

            $datas[] = $data;

            $no++;
            
        }

        return collect($datas);
    
    }

    public function headings(): array
    {
        return ["No", "NIK", "Employee Name", "Supervisor NIK", "Supervisor Name", "Regional", "jenis Ticket", "Sub-Feature",
                "Ticket Title", "Ticket Description", "Ticket Status", "Date"];
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
