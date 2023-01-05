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
                        <li class="breadcrumb-item active">Feature</li>
                    </ol>

                    <div class="container-fluid p-4 rounded shadow position-relative">
                        <button class="btn btn-danger btn-sm mb-5" id="delete-all">Delete All</button>
                        <table class="table">
                            <thead>
                              <tr>
                                <th scope="col p-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="master-check">
                                    </div>
                                </th>
                                <th scope="col">No</th>
                                <th scope="col">Feature Name</th>
                                <th scope="col">Action</th>
                              </tr>
                            </thead>
                            <tbody class="table-group-divider" id="table-feature">
                              
                            </tbody>
                          </table>

                        <button class="btn btn-dark rounded-circle shadow fs-4 featureAdd" id="buttonAdd" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i class="bi bi-plus"></i></button>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" id="dialog-modal">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Add New Feature</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="#" class="signin-form" id="" method="POST">
                    @csrf
                    <input type="hidden" name="" id="token" value="{{ Auth::user()->api_token }}">
                    <div class="form-group mt-4 pb-2">
                        <input type="text" 
                        class="form-control" 
                        id="feature_name" 
                        name="feature_name" 
                        autocomplete="off"
                        required>
                        <label class="form-control-placeholder" for="username">Feature Name</label>
                        <div id="feature_nameFeedback" class="invalid-feedback"></div>  
                    </div>
                
            </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="input-feature" data-bs-dismiss="modal">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection