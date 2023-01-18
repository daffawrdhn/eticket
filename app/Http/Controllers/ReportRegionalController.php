<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Employee;
use App\Models\Ticket;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ReportRegionalController extends BaseController
{
    public function index()
    {
        return view('containers.reports.report_regional');
    }
    
    
    public function getReport(Request $request, $id){
        try {
            if ($id == 0) {
                $datas = Ticket::with('feature', 'subFeature', 'ticketStatus')->get();
            }else{
                $employees = Employee::where('regional_id', $id)->get();
                // return $this->sendResponse($employees, 'succes');
                $datas = [];
                foreach($employees as $employee):
                    $ticket = Ticket::where('employee_id', $employee->employee_id)
                        ->with('feature', 'subFeature', 'ticketStatus')            
                        ->get();

                    foreach ($ticket as $t) {
                        $datas[] = $t;
                    }
                endforeach;
            }

            // return $this->sendResponse($datas, 'success');
            $tickets = [];
            $no =1;
            foreach($datas as $d){
                $getEmployee = Employee::with('regional')->where('employee_id', $d->employee_id)->first();
                $getSupervisor = Employee::where('employee_id', $d->supervisor_id)->first();
                $data['no'] = $no;
                $data['employee_id'] = $d->employee_id;
                $data['employee_name'] = $getEmployee->employee_name;
                $data['supervisor_id'] = $d->supervisor_id;
                $data['supervisor_name'] = $getSupervisor->employee_name;
                $data['regional'] = $getEmployee->regional->regional_name;
                $data['jenis_ticket'] = $d->feature['feature_name'];
                $data['sub_feature'] = $d->subFeature->sub_feature_name;
                $data['ticket_title'] = $d->ticket_title;
                $data['ticket_description'] = $d->ticket_description;
                $data['ticket_status'] = $d->ticketStatus['ticket_status_name'];

                $tickets[] = $data;
                $no++;
            }

            if ($request->ajax()) {
                $costumers = $tickets;
                return DataTables::of($costumers)
                ->dom('Bfrtip')
                ->buttons(
                    Button::make('excel')
                )
                ->toJson();
            }

        } catch (Exception $error) {
            return $this->sendError('Error validation', ['error' => $error]);
        }
    }
}
