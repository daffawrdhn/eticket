$(document).ready(function () {
    passwordValidation();

    $('#alert').hide();
    //login
    $('#form-login').submit(function (e) { 
        e.preventDefault();
        var data = {
            'employee_id' : $('#employee_id').val(),
            'password' : $('#password-field').val()
        }

    //    console.log(data);

        $.ajax({
            type : "POST",
            url : APP_URL + "api/login",
            data : data,
            dataType : "json",
            success : function(response){

                role = response.data.role['role_id']

                if (role === 1) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'You are not Administrator !',
                    })

                    window.location.href = APP_URL + "logout";
                }else{
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Login is Successfully',
                        showConfirmButton: false,
                        timer: 2000
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
    });


    $("#logout").click(function () {
        window.location.href = APP_URL + "logout";
    })
});