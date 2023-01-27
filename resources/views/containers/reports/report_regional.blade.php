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
                        <li class="breadcrumb-item active">Report Data Regional</li>
                    </ol>

                    <div class="container-fluid p-4 rounded shadow position-relative">
                        <div class="container-fluid" id="overflow">
                            <div class="container-fluid d-flex flex-row justify-content-between">
                                <div class="col-md-5 col-sm-12 d-flex flex-row">
                                    <div class="col-6 ">
                                        <input type="hidden" name="" id="token" value="{{ Auth::user()->api_token }}">
                                        <div id="select-regional" class="form-group border-danger">
                                            <select class="" style="width: 100%"  id="regional_id" name="regional_id" aria-label="Default select example"></select>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <button class="btn btn-primary" id="select-all">all data</button>
                                    </div>
                                </div>
                                <div>
                                    <button type="button" id="btnExport" class="btn btn-success"><i class="bi bi-file-earmark-excel-fill"></i></button>
                                    <iframe id="txtArea1" style="display:none"></iframe>
                                </div>
                            </div>
                            <div class="col-md-6" id="export" ></div>    
                            <table class="table" id="regionalTable">
                                <thead>
                                    <tr>
                                    <th scope="col" data-type="text">no</th>
                                    <th scope="col" data-type="text">NIk</th>
                                    <th scope="col" data-type="text">Employee Name</th>
                                    <th scope="col" data-type="text">Approval NIK</th>
                                    <th scope="col" data-type="text">Approval Name</th>
                                    <th scope="col" data-type="text">Regional</th>
                                    <th scope="col" data-type="text">Jenis Ticket</th>
                                    <th scope="col" data-type="text">Sub Feature</th>
                                    <th scope="col" data-type="text">Ticket Title</th>
                                    <th scope="col" data-type="text">Ticket Description</th>
                                    <th scope="col" data-type="text">Ticket Status</th>
                                    <th scope="col" data-type="text">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider" id="table-regional">
                                    
                                </tbody>
                                </table>

                        </div>    
                    </div>
                </div>
            </main>
        </div>
    </div>
   
    <script src="{{ asset('action/report/report_regional.js') }}"></script>
@endsection


