@extends('components.main')
@include('components.navbar')
@section('container')

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            @include('components.sidebar')
        </div>
        <div id="layoutSidenav_content" class="report_regional">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Reports</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Report Data Ticket SLA</li>
                    </ol>

                    <div class="container-fluid p-4 rounded shadow position-relative">
                        <div class="container-fluid" id="overflow">
                            <div class="container-fluid d-flex flex-row justify-content-between">
                                <div class="col-md-5 col-sm-12 d-flex flex-row mb-4">
                                    <div class="col-4">
                                        <input type="hidden" name="" id="token" value="{{ Auth::user()->api_token }}">
                                        <button class="btn btn-primary" id="select-all" data-id="">all data</button>
                                    </div>
                                    <div class="col-4">
                                        <div id="select-regionalId" class="form-group border-danger">
                                            <select class="" style="width: 100%" id="regional-select" name="regional_id" aria-label="Default select example"></select>
                                            <div id="regional_idFeedback" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tooltipButtonEdit">
                                    <button type="button" id="btnExport" regional-id="0" start-date="" end-date="" class="btn btn-success"><i class="bi bi-file-earmark-excel-fill"></i></button>
                                    <p class="tooltiptext">Export to Excel</p>
                                    <iframe id="txtArea1" style="display:none"></iframe>
                                </div>
                            </div>  
                            <table class="table" id="reportSlaTable">
                                <thead>
                                    <tr>
                                    <th scope="col" data-type="text">No Ticket</th>
                                    <th scope="col" data-type="text">Employee Id</th>
                                    <th scope="col" data-type="text">Regional</th>
                                    <th scope="col" data-type="text">Submit Date</th>
                                    <th scope="col" data-type="text">Approval 1 Date</th>
                                    <th scope="col" data-type="text">Approval 2 Date</th>
                                    <th scope="col" data-type="text">Approval 3 Date</th>
                                    <th scope="col" data-type="text">Final Approval Date</th>
                                    <th scope="col" data-type="text">Reject Date</th>
                                    <th scope="col" data-type="text">On Progress Date</th>
                                    <th scope="col" data-type="text">Finish Date</th>
                                    <th scope="col" data-type="text">Status</th>
                                    <th scope="col" data-type="text">Total SLA</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider" id="table-sla">
                                    
                                </tbody>
                                </table>

                        </div>    
                    </div>
                </div>
            </main>
        </div>
    </div>


    <script src="{{ asset('action/report/report_sla.js') }}"></script>
@endsection


