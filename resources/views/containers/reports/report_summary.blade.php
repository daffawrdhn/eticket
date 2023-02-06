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
                            <div class="col-md-5 col-sm-12 d-flex flex-row mb-4">
                                <input type="hidden" name="" id="token" value="{{ Auth::user()->api_token }}">
                                <div class="col-4">
                                    <button class="btn btn-primary" id="select-all">all data</button>
                                </div>
                                <div class="col-4">
                                    <button class="btn btn-light border" data-bs-toggle="modal" data-bs-target="#search">Search<i class="bi bi-search"></i></button>
                                </div>
                            </div>
                            <div class="tooltipButtonEdit">
                                <button type="button" id="btnExport" regional-id="0" start-date="" end-date="" class="btn btn-success"><i class="bi bi-file-earmark-excel-fill"></i></button>
                                <p class="tooltiptext">Export to Excel</p>
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
    <div class="modal fade" id="search" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" id="dialog-modal">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Search Report Regional</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" id="closeSearch" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" id="alert" role="alert">
                    please fill out this field
                </div>
                <div class="spinner-border container-fluid text-center" id="isLoading" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div id="select-regionalId" class="form-group border-danger">
                    <label class="label-form" for="regional">Select Regional</label>
                    <select class="" style="width: 100%" id="regional-select" name="regional_id" aria-label="Default select example"></select>
                    <div id="regional_idFeedback" class="invalid-feedback"></div>
                </div>
                <div class="form-group mt-4 pb-2 date">
                    <label class="label-form" for="start_date">Start Date</label>
                    <input type="date"
                    class="form-control date" 
                    id="start-date" 
                    name="start_date" 
                    autocomplete="off"
                    required>
                    <div id="start-dateFeedback" class="invalid-feedback"></div>
                </div>
                <div class="form-group mt-4 pb-2 date">
                    <label class="label-form" for="end_date">End Date</label>
                    <input type="date"
                    class="form-control date" 
                    id="end-date" 
                    name="end_date" 
                    min=""
                    autocomplete="off"
                    required>
                    <div id="end-dateFeedback" class="invalid-feedback"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="searchReport">Save</button>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('action/report/report_summary.js') }}"></script>
@endsection


