<?php 
namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Employee;
use App\Models\Regional;
use App\Models\Ticket;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Constraint\Count;
use Yajra\DataTables\Facades\DataTables;

class ReportSummaryController extends BaseController
{
    public function index()
    {
        return view('containers.reports.report_summary');
    }

    public function getDataSumary(Request $request){
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
                    'total_ticket' => $total_ticket->total_ticket.' tickets',
                    'open' => $open.' tickets',
                    'approve' => $approve.' tickets',
                    'reject' => $reject.' tickets'
                ];
                
            }

            $datas[] = [
                'regional_name' => 'Total Jumlah',
                'approve' => $totalApprove.' tickets',
                'reject' => $totalReject.' tickets',
                'open' => $totalOpen.' tickets',
                'total_ticket' => $totalTicket.' tickets'
            ];

            if ($request->ajax()) {
                $costumers = $datas;
                return DataTables::of($costumers)->toJson();
            }

        } catch (Exception $error) {
            return $this->sendError('Error validation', ['error' => $error]);
        }
    }


    public function sumaryDashboard(){
        try {
                $tickets = Ticket::all();
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
                

            $datas['approve'] = $approve;
            $datas['reject'] = $reject;
            $datas['open'] = $open;
            $datas['total_ticket'] = Count(Ticket::all());

            return $this->sendResponse($datas, 'success');

        } catch (Exception $error) {
            return $this->sendError('Error validation', ['error' => $error]);
        }
    }
}



?>