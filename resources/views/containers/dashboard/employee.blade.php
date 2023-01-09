@extends('components.main')
@include('components.navbar')
@section('container')

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            @include('components.sidebar')
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Settings</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">User</li>
                    </ol>

                    <div class="container-fluid p-4 rounded shadow">
                        
                        <div class="container-fluid" id="overflow">
                            <table class="table table-striped table-hover" id="employeeTable">
                                <thead>
                                    <tr >
                                        <th scope="col">Nik</th>
                                        <th scope="col">No.Ktp</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Role</th>
                                        <th scope="col">Organization</th>
                                        <th scope="col">Regional</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                    <tr id="loading-table">
                                        <td colspan="8" align="center">
                                            <div class="container-fluid spinner-border mt-4 d-flex justify-content-center" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </td>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider" id="table-employee">
                                
                                </tbody>
                            </table>
                        </div>

                        <button class="btn btn-dark rounded-circle shadow fs-3 text-center userAdd" id="buttonAdd" data-bs-toggle="modal" data-bs-target="#modalAddUser"><i class="bi bi-plus"></i></button>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <div class="modal fade" id="modalAddUser" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="modalAddUserLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" style="">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalAddUserLabel">Add New User</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="#" class="signin-form" id="" method="POST">
                    @csrf
                    <input type="hidden" name="" id="token" value="{{ Auth::user()->api_token }}">
                    
                    <div class="form-group mt-4 pb-2">
                        <input type="text" 
                        class="form-control" 
                        id="employee_name" 
                        name="employee_name" 
                        autocomplete="off"
                        required>
                        <label class="form-control-placeholder" for="employee_name">Nama</label>
                        <div id="employee_nameFeedback" class="invalid-feedback"></div>  
                    </div>
                    <div class="form-group mt-4 pb-2">
                        <input type="text" 
                        class="form-control" 
                        id="employee_email" 
                        name="employee_email" 
                        autocomplete="off"
                        required>
                        <label class="form-control-placeholder" for="employee_email">Email</label>
                        <div id="employee_emailFeedback" class="invalid-feedback"></div>  
                    </div>
                    <div class="form-group mt-4 pb-2">
                        <input type="text" 
                        class="form-control" 
                        id="employee_ktp" 
                        name="employee_ktp" 
                        autocomplete="off"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');"
                        maxlength="18"
                        required>
                        <label class="form-control-placeholder" for="employe_ktp">No KTP</label>
                        <div id="employee_ktpFeedback" class="invalid-feedback"></div>  
                    </div>
                    <div class="form-group mt-4 pb-2 date">
                        <input type="text"
                        class="form-control date" 
                        id="employee_birth" 
                        name="employee_birth" 
                        autocomplete="off"
                        required>
                        <label class="form-control-placeholder" for="employee_birth">date of birth</label>
                        <div id="employee_birthFeedback" class="invalid-feedback"></div>
                    </div>
                    <div class="form-group mt-4 pb-2 date">
                        <input type="text"
                        class="form-control date" 
                        id="join_date" 
                        name="join_date" 
                        min=""
                        autocomplete="off"
                        required>
                        <label class="form-control-placeholder" for="join_date">Join Date</label>
                        <div id="join_dateFeedback" class="invalid-feedback"></div>
                    </div>
                    <div class="form-group mt-4 pb-2 date">
                        <input type="text"
                        class="form-control date" 
                        id="quit_date" 
                        name="quit_date" 
                        autocomplete="off"
                        required>
                        <label class="form-control-placeholder" for="quit_date">Quit Date</label>
                        <div id="quit_dateFeedback" class="invalid-feedback"></div>
                    </div>
                    <div class="form-group mt-4">
                        <select class="form-select form-control" id="supervisor_id" name="supervisor_id" style="width: 100%" aria-label="Default select example"></select>
                        
                    </div>
                    <div class="form-group mt-4">
                        <select class="form-select mt-4 overflow-auto" data-live-search="true" id="role_id" name="role_id" style="width: 100%; margin-bottom:20px"  aria-label="Default select example" style="height: 50px"></select>
                        
                    </div>
                    <div class="form-group mt-4">
                        <select class="form-select  mt-4" id="organization_id" name="organization_id" style="width: 100%" aria-label="Default select example"></select>
                        </div>
                    <div class="form-group mt-4">
                        <select class="form-select  mt-4" id="regional_id" name="regional_id" aria-label="Default select example" style="width: 100%"  style="height: 50px"></select>
                            </div>
                
            </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="input-user">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" id="dialog-modal">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Set New Quit Date</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="#" class="signin-form" id="" method="POST">
                    @csrf
                    <input type="hidden" id="status" name="status" data-id="">
                    <div class="form-group mt-4 pb-2 date">
                        <input type="text"
                        class="form-control date" 
                        id="new_quit_date" 
                        name="new_quit_date" 
                        autocomplete="off"
                        required>
                        <label class="form-control-placeholder" for="new_quit_date">Quit Date</label>
                    </div>
                
            </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="input-quit-date">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="loading" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="loaingLabel" aria-hidden="true">
        <div class="modal-dialog" id="dialog-modal">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h5>Loading.....</h5>
                    <div class="container-fluid spinner-border mt-4" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('action/user/get_data.js') }}"></script>
    <script src="{{ asset('action/user/user.js') }}"></script>
    <script src="{{ asset('action/user/select_data.js') }}"></script>
@endsection