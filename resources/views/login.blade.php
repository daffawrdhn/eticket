@extends('components.main')



@section('container')
<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-5">
                <div class="wrap">
                    <div class="d-flex justify-content-center pt-2">
                        <img src="asset/Eticket.png" style="height: 200px" alt="">
                    </div>
                    <div class="login-wrap p-4 p-md-5">
                        <div class="d-flex">
                            <div class="w-100">
                                <h3 class="mb-4">Sign In</h3>
                            </div>
                                <div class="w-100"></div>
                        </div>
                        <div class="mb-5"><div class="alert alert-danger" id="alert" role="alert"></div></div>
                        <form action="#" class="signin-form" id="form-login" method="POST">
                            @csrf
                            <div class="form-group mt-3 pb-2">
                                <input type="number" 
                                class="form-control" 
                                id="employee_id" 
                                name="employee_id" 
                                autocomplete="off"
                                required>
                                <label class="form-control-placeholder" for="username">NIK</label>
                            </div>
                            <div id="validation-employeeId" class="invalid-feedback"></div>
                            <div class="form-group pb-2">
                                <input id="password-field" 
                                type="password" 
                                name="password"  
                                class="form-control" 
                                pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-]).{8,12}$"
                                required>

                                <label class="form-control-placeholder" for="password">Password</label>
                                <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>

                                <div id="validation-password" class="invalid-feedback"></div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="form-control btn btn-primary rounded submit px-3">Sign In</button>
                            </div>
                            <div class="form-group d-md-flex">
                                <div class="w-50 text-left"></div>
                                <div class="w-50 text-md-right">
                                    <a href="#">Forgot Password</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
    {{-- <div class="container-fluid">
        <div class="container-fluid col-lg-4 col-md-6 col-sm-12 p-2 position-absolute top-50 start-50 translate-middle">
                <div class="col-lg-12 col-md-12 col-sm-12 p-4 shadow rounded bg-light position-relative">
                    <img src="/asset/Eticket.png" class="rounded-circle position-absolute top-0 start-50 translate-middle shadow" style="height: 200px;" alt="">
                    
                    <div>
                        <h5 class="mb-4 mt-5 pt-5 text-center border-bottom pb-3 fs-1 fw-bold">LOGIN</h5>    
                    </div>                    
                    <div class="alert alert-danger" id="alert" role="alert"></div>
                    <form id="form-login" action="" method="POST">
                        @csrf
                        <div class="mb-3">
                            <input type="number" 
                            placeholder="NIK" 
                            class="form-control form-control-lg rounded-pill ps-4" 
                            name="employee_id" 
                            id="employee_id" required>
                        </div>
                        <div class="mb-3">
                            <input type="password" 
                            placeholder="Password"
                            class="form-control rounded-pill ps-4 form-control-lg" 
                            id="password" 
                            name="password" 
                            pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                            title="Must contain at least one  number and one uppercase and lowercase letter, and at least 8 or more characters"
                            required>
                            <div id="validation-password" class="invalid-feedback"></div>
                        </div>
                        <p><a href="" class="fw-bold text-dark">forgot password ?</a></p>
                        <div class="container-fluid d-flex justify-content-center mt-5">
                            <button type="submit" id="bg-yellow" name="login" class="btn btn-primary rounded-pill ps-4 pe-4 text-dark fw-bold">L O G I N</button>
                        </div>
                    </form>
                </div>
        </div>
    </div> --}}
@endsection

