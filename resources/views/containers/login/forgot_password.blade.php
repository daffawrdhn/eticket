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
                        <h5 class="card-title mb-5">Input New Password</h5>
                        <div class="alert alert-danger mb-4" id="alert" role="alert"></div>
                        <form action="#" class="signin-form" id="forgot-password" method="">
                            @csrf
                            <input type="hidden" name="" id="token" value="{{ $token }}">
                            <div class="form-group">
                                <div class="" id="hover">
                                    <div class="input-group description" id="show_hide_password">
                                        <input type="password" 
                                        id="password-field" 
                                        name='password' 
                                        class="form-control" 
                                        name="password" 
                                        required>
                                        <label class="form-control-placeholder" for="password">New Password</label>
                                        <div class="input-group-append">
                                            <a class="btn btn-outline-secondary pt-2 rounded-end"><i class="bi bi-eye-slash" aria-hidden="true"></i></a>
                                        </div>
                                        <div id="password-fieldFeedback" class="invalid-feedback"></div>   
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="">
                                    <div class="input-group" id="show-hide-confirm">
                                        <input type="password" 
                                        id="confirm-password" 
                                        name='password' 
                                        class="form-control" 
                                        name="password" 
                                        required>
                                        <label class="form-control-placeholder" for="confirm-password">Confirm Password</label>
                                        <div class="input-group-append">
                                            <a class="btn btn-outline-secondary pt-2 rounded-end"><i class="bi bi-eye-slash" aria-hidden="true"></i></a>
                                        </div>
                                        <div id="confirm-passwordFeedback" class="invalid-feedback"></div>   
                                    </div>
                                </div>
                            </div>
                              
                            <div class="form-group">
                                <button type="submit" id="button-forgot" class="form-control btn btn-primary rounded submit px-3 mb-2">S U B M I T</button>
                                <a href="/" class="form-control btn btn-primary rounded submit px-3">B A C K</a>
                            </div>
                        </form>
                        <div id="pswd_desc">
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
