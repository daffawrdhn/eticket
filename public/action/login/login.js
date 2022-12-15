$(document).ready(function () {

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

                console.log(response);
                
                if (response.success === true) {
                    console.log('ok');
                    // location.href = APP_URL + "myList"
                }else{
                    alert("login gagal")
                }
                
            }
        })
    });
});