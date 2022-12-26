$(document).ready(function () {
    


    $("#button-forgot").click(function (e) { 
        e.preventDefault();
        
        var newPassword = $('#password-field').val()
        var confirmPassword = $('#confirm-password').val()
        var token = $('#token').val()
        var pwdValidation = /^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&()'[\]"?+-/*={}.,;:_])([a-zA-Z0-9!@#$%^&()'[\]"?+-/*={}.,;:_]{8,12})$/;

        var data = {
            'new_password' : newPassword,
            'new_password_confirm' : confirmPassword,
        }

        if (newPassword.match(pwdValidation)) {

            if (newPassword === confirmPassword) {
                $('#confirm-password').removeClass('is-invalid');
                $('#password-field').removeClass('is-invalid');

                $.ajax({
                type : "POST",
                url : APP_URL + "api/forgot-password",
                data : data,
                dataType : "json",
                beforeSend: function(xhr, settings) { 
                    xhr.setRequestHeader('Authorization','Bearer ' + token ); 
                },
                success : function(response){
    
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 2000
                    }).then((result) => {
                        if (result.dismiss === Swal.DismissReason.timer) {
                            location.href = APP_URL;
                        }
                      })
                    
                },
                error:function(response){
                    if (!response.success) {
                            $('#alert').show();
                            $('#alert').html(response.responseJSON.data.error);
                        
                    }
                }
            })
            }else{
                $('#confirm-password').addClass('is-invalid');
                $('#hover').addClass('is-invalid');
                $('#confirm-passwordFeedback').html('confirm password not match')
            }
        }else{
            $('#password-field').addClass('is-invalid');
            $('#password-fieldFeedback').html('please match the request format')
        }


        
    });


});