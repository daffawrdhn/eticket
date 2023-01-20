@extends('components.main')
@include('components.navbar')
@section('container')

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            @include('components.sidebar')
        </div>
        <div id="layoutSidenav_content" class="report_sumary">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Reports</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Report Data Summary</li>
                    </ol>
                    <div class="container-fluid p-4 rounded shadow position-relative">
                        <div class="container-fluid d-flex flex-row justify-content-between">
                            <div class="col-md-5 col-sm-12 d-flex flex-row mb-5">
                                <div class="col-6 ">
                                    <input type="hidden" name="" id="token" value="{{ Auth::user()->api_token }}">
                                </div>
                            </div>
                            <div>
                                <button type="button" id="btnExport" class="btn btn-success"><i class="bi bi-file-earmark-excel-fill"></i></button>
                                <iframe id="txtArea1" style="display:none"></iframe>
                            </div>
                        </div>
                        <div class="container-fluid" id="overflow">    
                            <table class="table table-striped" id="summaryTable">
                                <thead>
                                    <tr>
                                        <th scope="col">Regional</th>
                                        <th scope="col">Ticket Proccess</th>
                                        <th scope="col">Ticket Approval</th>
                                        <th scope="col">Ticket Reject</th>
                                        <th scope="col">Total Ticket</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider" id="table-summary">
                                    
                                </tbody>
                                </table>

                        </div>    
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="{{ asset('action/report/report_summary.js') }}"></script>
@endsection


