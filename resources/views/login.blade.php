@extends('components.main')



@section('container')
    <div class="container-fluid">
        <div class="container-fluid col-lg-4 col-md-6 col-sm-12 p-2 position-absolute top-50 start-50 translate-middle">
                <div id="bg-blue" class="col-lg-12 col-md-12 col-sm-12 p-3 shadow rounded">
                    <h5 class="mb-4 text-center border-bottom pb-3 fw-bold">Login</h5>
                    <div class="container text-center">
                        <p class="fw-bold">Welcome</p>
                        <p>Login Administrator E-Ticket</p>
                    </div>
                    <div class="alert alert-danger" id="alert" role="alert"></div>
                    <form id="form-login" action="" method="POST">
                        {{ csrf_field() }}
                        <div class="mb-3">
                            <label for="employee_id" class="form-label">NIK</label>
                            <input type="text" class="form-control rounded-pill ps-4" name="employee_id" id="employee_id" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" 
                            class="form-control rounded-pill ps-4" 
                            id="password" 
                            name="password" 
                            pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                            title="Must contain at least one  number and one uppercase and lowercase letter, and at least 8 or more characters"
                            required>
                            <div id="validation-password" class="invalid-feedback"></div>
                        </div>
                        <p>lupa password ?? <a href="" class="fw-bold text-dark">click here!</a></p>
                        <div class="container-fluid d-flex justify-content-center mt-5">
                            <button type="submit" id="bg-yellow" name="login" class="btn btn-primary rounded-pill ps-4 pe-4 text-dark fw-bold">L O G I N</button>
                        </div>
                        
                    </form>
                </div>
        </div>
    </div>
@endsection

