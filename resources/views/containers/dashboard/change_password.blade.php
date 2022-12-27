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
                    <h1 class="mt-4">Change Password</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">change password</li>
                    </ol>

                    <div class="container-fluid">
                        <div class="card col-lg-6 col-md-6  container-fluid d-flex justify-content-center shadow">
                            <div class="card-body">
                                <h5 class="card-title mb-5">Input New Password</h5>
                                <div class="alert alert-danger mb-4" id="alert" role="alert"></div>
                                <form action="#" class="signin-form" id="forgot-password" method="">
                                    @csrf
                                    <input type="hidden" name="" id="token" value="{{ Auth::user()->api_token }}">
                                    <div class="form-group">
                                        <div class="" id="hover">
                                            <div class="input-group description mb-4" id="show_hide_password">
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
                                                <label class="form-control-placeholder" for="password">Confirm Password</label>
                                                <div class="input-group-append">
                                                    <a class="btn btn-outline-secondary pt-2 rounded-end"><i class="bi bi-eye-slash" aria-hidden="true"></i></a>
                                                </div>
                                                <div id="confirm-passwordFeedback" class="invalid-feedback"></div>   
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <button type="submit" id="button-forgot" class="form-control btn btn-primary rounded submit px-3">S U B M I T</button>
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
            </main>
        </div>
    </div>
    
    
@endsection