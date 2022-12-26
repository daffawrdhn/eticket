@extends('components.main')


@section('container')
    

<section class="ftco-section">
    <div class="container">
        <div class="card col-md-8 col-lg-8 position-absolute top-50 start-50 translate-middle shadow">
            <div class="row g-0 m-2">
                <div class="col-md-6 ">
                    <img src="/asset/Eticket.png" class="col-lg-12 col-md-12 col-sm-4 rounded-start" alt="">
                </div>
                <div class="col-md-6">
                    <div class="card-body">
                        <h5 class="card-title mb-5">Check Your Data</h5>
                        <div class="alert alert-danger mb-2" id="alert" role="alert"></div>
                        <form action="#" class="signin-form" id="form-login" method="POST">
                            @csrf
                            <div class="form-group mt-4 pb-2">
                                <input type="text" 
                                class="form-control" 
                                id="employee_id" 
                                name="employee_id" 
                                autocomplete="off"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');"
                                required>
                                <label class="form-control-placeholder" for="employee_id">NIK</label>
                                <div id="employee_idFeedback" class="invalid-feedback"></div>
                            </div>
                            <div class="form-group mt-4 pb-2">
                                <input type="text" 
                                class="form-control" 
                                id="employee_ktp" 
                                name="employee_ktp" 
                                autocomplete="off"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');"
                                required>
                                <label class="form-control-placeholder" for="employee_ktp">No. KTP</label>
                                <div id="employee_ktpFeedback" class="invalid-feedback"></div>
                            </div>

                            <div class="form-group mt-4 pb-2 date">
                                <input type="text"
                                class="form-control" 
                                id="employee_birth" 
                                name="employee_birth" 
                                autocomplete="off"
                                required>
                                <label class="form-control-placeholder" for="employee_birth">date of birth</label>
                                
                            </div>
                              
                            <div class="form-group">
                                <button type="submit" id="ceck-employee" class="form-control btn btn-primary rounded submit px-3">S U B M I T</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@endsection
