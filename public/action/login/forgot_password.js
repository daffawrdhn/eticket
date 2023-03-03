$(document).ready(function () {
    
    $("#button-forgot").click(function (e) { 
        e.preventDefault();
        console.log('ok');
        var newPassword = $('#password-field').val()
        var confirmPassword = $('#confirm-password').val()
        var token = $('#token').val()
        var validation = new Validation
        var validationPassword = validation.passwordValidation(newPassword)
        var data = {
            'new_password' : newPassword,
            'new_password_confirm' : confirmPassword,
        }
        if (validationPassword.validation == true) {

            if (newPassword === confirmPassword) {
                $('#confirm-password').removeClass('is-invalid');
                $('#password-field').removeClass('is-invalid');
                $('#confirm-passwordFeedback').html('password is not match');

                var ajax = new Ajax(APP_URL + "api/forgot-password", data, "POST")
                ajax.ajaxForgotPassword(token)

                // let timerInterval
                // Swal.fire({
                // title: 'Loading!',
                // timer: 2000,
                // timerProgressBar: true,
                // didOpen: () => {
                //     Swal.showLoading()
                // },
                // willClose: () => {
                //     clearInterval(timerInterval)
                // }
                // }).then((result) => {
                //     if (result.dismiss === Swal.DismissReason.timer) {
                //         $.ajax({
                //             type : "POST",
                //             url : APP_URL + "api/forgot-password",
                //             data : data,
                //             dataType : "json",
                //             beforeSend: function(xhr, settings) { 
                //                 xhr.setRequestHeader('Authorization','Bearer ' + token ); 
                //             },
                //             success : function(response){
                
                //                 Swal.fire({
                //                     position: 'center',
                //                     icon: 'success',
                //                     title: response.message,
                //                     showConfirmButton: false,
                //                     timer: 2000
                //                 }).then((result) => {
                //                     if (result.dismiss === Swal.DismissReason.timer) {
                //                         location.href = APP_URL;
                //                     }
                //                   })
                                
                //             },
                //             error:function(response){
                //                 if (!response.success) {
                //                         $('#alert').show();
                //                         $('#alert').html(response.responseJSON.data.error);
                                    
                //                 }
                //             }
                //         })
                //     }
                // })

                
            }else{
                $('#confirm-password').addClass('is-invalid');
                $('#confirm-passwordFeedback').html('confirm password is not match');
            }
        }


        
    });


});