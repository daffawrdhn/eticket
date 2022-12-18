$(document).ready(function () {

    $('#alert').hide();
    //login
    $('#form-login').submit(function (e) { 
        e.preventDefault();
        var data = {
            'employee_id' : $('#employee_id').val(),
            'password' : $('#password').val()
        }

       console.log(data);
        
        $.ajax({
            type : "POST",
            url : APP_URL + "api/login",
            data : data,
            dataType : "json",
            success : function(response){

                role = response.data.role['role_name']

                if (role === 'user') {
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
                    })

                    location.href = APP_URL + "dashboard";
                }
                
            },
            error:function(response){
                if (!response.success) {
                    $('#alert').show();
                    $('#alert').html(response.responseJSON.data.error);
                }
            }
        })
    });


    $("#logout").click(function () {
        window.location.href = APP_URL + "logout";
    })
});