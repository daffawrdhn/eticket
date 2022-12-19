@extends('components.main')



@section('container')
<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-5">
                <div class="wrap">
                    <div class="d-flex justify-content-center pt-2">
                        <img src="asset/Eticket.png" style="height: 180px" alt="">
                    </div>
                    <div class="login-wrap p-2 p-md-3">
                        <div class="d-flex">
                            <div class="w-100">
                                <h3 class="mb-4">Login</h3>
                            </div>
                                <div class="w-100"></div>
                        </div>
                        <div class="alert alert-danger" id="alert" role="alert"></div>
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
                                <label class="form-control-placeholder" for="username">NIK</label>
                            </div>
                            <div class="form-group">
                                <div class="" id="hover">
                                    <div class="input-group" id="show_hide_password">
                                        <input type="password" 
                                        id="password-field" 
                                        name='password' 
                                        class="form-control" 
                                        name="password" 
                                        required>
                                        <label class="form-control-placeholder" for="password">Password</label>
                                        <div class="input-group-append">
                                            <a class="btn btn-outline-secondary pt-2 rounded-end"><i class="bi bi-eye-slash" aria-hidden="true"></i></a>
                                        </div>
                                        <div id="password-fieldFeedback" class="invalid-feedback"></div>   
                                    </div>
                                </div>
                              </div>
                              
                            <div class="form-group">
                                <button type="submit" class="form-control btn btn-primary rounded submit px-3">L O G I N</button>
                            </div>
                            <div class="form-group d-md-flex">
                                <div class="w-50 text-left"></div>
                                <div class="w-50 text-md-right">
                                    <a href="#">Forgot Password ?</a>
                                </div>
                            </div>
                        </form>
                        <div id="pswd_info">
                            <p>Password must meet the following requirements:</p>
                            <ul>
                                <li id="lowerCase" class="">At least <strong>one letter</strong></li>
                                <li id="upperCase" class="">At least <strong>one capital letter</strong></li>
                                <li id="number" class="">At least <strong>one number</strong></li>
                                <li id="length" class="">Be at least <strong>8 - 12 characters</strong></li>
                                <li id="specialChar" class="">Be at least <strong>One Special Characters</strong></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

