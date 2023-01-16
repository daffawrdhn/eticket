@extends('components.main')
@include('components.navbar')
@section('container')

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            @include('components.sidebar')
        </div>
        <div id="layoutSidenav_content" class="sub_feature">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Settings</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Sub Feature</li>
                    </ol>

                    <div class="container-fluid p-4 rounded shadow position-relative">
                        <div class="container-fluid" id="overflow">    
                            <table class="table" id="subFeatureTable">
                                <thead>
                                    <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Jenis Tiket</th>
                                    <th scope="col">Sub Feature Name</th>
                                    <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider" id="table-sub_feature">
                                    
                                </tbody>
                                </table>

                            <button class="btn btn-dark rounded-circle shadow fs-4 sub_featureAdd" id="buttonAdd" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i class="bi bi-plus"></i></button>
                    
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
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Add New Sub Feature</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="#" class="signin-form" id="" method="POST">
                    @csrf
                    <input type="hidden" name="" id="token" value="{{ Auth::user()->api_token }}">
                    <div id="select-feature" class="form-group border-danger">
                        <label class="label-form" for="organization_id">Select Feature</label>
                        <select class="" style="width: 100%" id="feature_id" name="feature_id" aria-label="Default select example"></select>
                        <div id="feature_idFeedback" class="invalid-feedback"></div>
                    </div>
                    <div class="form-group mt-4 pb-2">
                        <input type="text" 
                        class="form-control" 
                        id="sub_feature_name" 
                        name="sub_feature_name" 
                        autocomplete="off"
                        required>
                        <label class="form-control-placeholder" for="sub_feature_name">Sub Feature Name</label>
                        <div id="sub_feature_nameFeedback" class="invalid-feedback"></div>
                    </div>
            </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="input-sub_feature">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script src="{{ asset('action/sub_feature/sub_feature.js') }}"></script>
@endsection


