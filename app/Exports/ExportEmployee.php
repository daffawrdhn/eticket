<?php

namespace App\Exports;

use App\Models\Employee;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;

class ExportEmployee implements FromCollection, WithHeadings, ShouldAutoSize
{
    private $regionalId;

    public function __construct($regionalId){
        $this->regionalId = $regionalId;
    }
    public function collection()
    {
        if($this->regionalId == 0){
            $datas = Employee::orderBy('created_at', 'asc')->with('Role', 'Organization', 'Regional')->get();
        }else{
            $datas = Employee::orderBy('created_at', 'asc')->where('regional_id', $this->regionalId)->with('Role', 'Organization', 'Regional')->get();
        }
        $isNow = Carbon::now();

        $no = 1;
        $dataEmployee = [];
        foreach($datas as $d){
            $employee = Employee::where('employee_id', $d->supervisor_id)->first();
            $data['no'] = $no;
            $data['employee_id'] = $d->employee_id;
            $data['employee_ktp'] = $d->employee_ktp;
            $data['employee_name'] = $d->employee_name;
            $data['employee_email'] = $d->employee_email;
            $data['supervisor_id'] = $d->supervisor_id;
            $data['supervisor_name'] = $employee->employee_name;
            $data['role_name'] = $d->role['role_name'];
            $data['regional_name'] = $d->regional['regional_name'];
            $data['organization_name'] = $d->organization['organization_name'];
            if ($d->quit_date > $isNow) {
                $data['status'] = 'Active';
            }else{
                $data['status'] = 'Non Active';
            }

            $dataEmployee[] = $data;

            $no++;
        }


        return collect($dataEmployee);
    }

    public function headings(): array
    {
        return ["No", "NIK","Employee KTP", "Employee Name", "Employee Email" , "Supervisor NIK", "Supervisor Name", "Role", "Organization", "Regional",
                "Status"];
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
