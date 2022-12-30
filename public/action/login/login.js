$(document).ready(function () {
    passwordValidation();
    checkNik()

    $('#alert').hide();
    //login
    $('#form-login').submit(function (e) { 
        e.preventDefault();
        var pwdValidation = /^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&()'[\]"?+-/*={}.,;:_])([a-zA-Z0-9!@#$%^&()'[\]"?+-/*={}.,;:_]{8,12})$/;
        var data = {
            'employee_id' : $('#employee_id').val(),
            'password' : $('#password-field').val()
        }

        if (data.employee_id.length >= 8) {
            if (data.password.match(pwdValidation)) {
                let timerInterval
                Swal.fire({
                title: 'Loading!',
                timer: 2000,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading()
                },
                willClose: () => {
                    clearInterval(timerInterval)
                }
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.timer) {
                        $.ajax({
                            type : "POST",
                            url : APP_URL + "api/login",
                            data : data,
                            dataType : "json",
                            success : function(response){
                
                                role = response.data.role['role_name']
                
                                if (role != 'administrator') {
                                    Swal.fire({
                                        icon : 'error',
                                        title: 'You are not Administrator!!',
                                        showDenyButton: false,
                                        showCancelButton: false,
                                        confirmButtonText: 'Ok',
                                      }).then((result) => {
                                        /* Read more about isConfirmed, isDenied below */
                                        if (result.isConfirmed) {
                                            window.location.href = APP_URL + "logout";
                                        } 
                                      })
                                }else{
                                    Swal.fire({
                                        position: 'center',
                                        icon: 'success',
                                        title: 'Login is Successfully',
                                        showConfirmButton: false,
                                        timer: 1000
                                    }).then((result) => {
                                        if (result.dismiss === Swal.DismissReason.timer) {
                                            location.href = APP_URL + "dashboard";
                                        }
                                      })
                
                                    
                                }
                                
                            },
                            error:function(response){
                                if (!response.success) {
                
                                    if (response.status === 403) {
                                        Swal.fire({
                                            icon : 'error',
                                            title: 'You are not Administrator!!',
                                            showDenyButton: false,
                                            showCancelButton: false,
                                            confirmButtonText: 'Ok',
                                          }).then((result) => {
                                            /* Read more about isConfirmed, isDenied below */
                                            if (result.isConfirmed) {
                                                window.location.href = APP_URL + "logout";
                                            } 
                                          })
                
                                    }else{
                                        $('#alert').show();
                                        $('#alert').html(response.responseJSON.data.error);
                                    }
                                }
                            }
                        })
                    }
                })
            }
        }

        

        
    });


    $("#logout").click(function () {
        window.location.href = APP_URL + "logout";
    })
});