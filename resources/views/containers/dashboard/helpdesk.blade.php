@extends('components.main')
@include('components.navbar')
@section('container')

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            @include('components.sidebar')
        </div>
        <div id="layoutSidenav_content" class="approval">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Settings</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Approval>>Helpdesk</li>
                    </ol>

                    <div class="container-fluid p-4 rounded shadow position-relative">
                        
                        <div class="container-fluid" id="overflow">    
                            <table class="table" id="approvalTable">
                                <thead>
                                    <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Regional Approval</th>
                                    <th scope="col">NIK</th>
                                    <th scope="col">Employee Name</th>
                                    <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider" id="table-approval">
                                    
                                </tbody>
                            </table>
                            <div class="tooltipButtonAdd">
                                <button class="btn btn-dark rounded-circle shadow fs-4 approvalAdd" id="buttonAdd" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i class="bi bi-plus"></i></button>
                                <p class="tooltiptext">Add Helpdesk</p>
                            </div>
                    
                        </div>    
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" id="dialog-modal">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Add New Approval Helpdesk</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="#" class="signin-form" id="" method="POST">
                    @csrf
                    <input type="hidden" name="" id="token" value="{{ Auth::user()->api_token }}">
                    <div id="select-regional" class="form-group border-danger">
                        <label class="label-form" for="employee_id">Select Regional</label>
                        <select class="" style="width: 100%"  id="regional_id" name="regional_id" aria-label="Default select example"></select>
                        <div id="regional_idFeedback" class="invalid-feedback"></div>
                    </div>
                    <div id="select-employee" data-id="" class="form-group border-danger">
                        <label class="label-form" for="employee_id">Select Employee</label>
                        <select class="" style="width: 100%"  id="employee_id" name="employee_id" aria-label="Default select example"></select>
                        <div id="employee_idFeedback" class="invalid-feedback"></div>
                    </div>
            </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="input-approval">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script src="{{ asset('action/approval/helpdesk.js') }}"></script>
@endsection


