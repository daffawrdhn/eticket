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
                            <div class="col-4">
                                <input type="hidden" name="" id="token" value="{{ Auth::user()->api_token }}">
                                <div id="select-regional" class="form-group border-danger">
                                    <label class="label-form" for="employee_id">Select Regional</label>
                                    <select class="" style="width: 100%"  id="regional_id" name="regional_id" aria-label="Default select example"></select>
                                    <div id="regional_idFeedback" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6" id="export" ></div>    
                            <table class="table" id="regionalTable">
                                <thead>
                                    <tr>
                                    <th scope="col">no</th>
                                    <th scope="col">NIk</th>
                                    <th scope="col">Employee Name</th>
                                    <th scope="col">Supervisor NIK</th>
                                    <th scope="col">Supervisor Name</th>
                                    <th scope="col">Regional</th>
                                    <th scope="col">Jenis Ticket</th>
                                    <th scope="col">Sub Feature</th>
                                    <th scope="col">Ticket Title</th>
                                    <th scope="col">Ticket Description</th>
                                    <th scope="col">Ticket Status</th>
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


