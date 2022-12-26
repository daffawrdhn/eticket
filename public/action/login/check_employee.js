$(document).ready(function () {
    $("#ceck-employee").click(function (e) { 
        e.preventDefault();
        

        $('#alert').hide();

        var data = {
            'employee_id' : $('#employee_id').val(),
            'employee_ktp' : $('#employee_ktp').val(),
            'employee_birth' : $('#employee_birth').val(),
        }

        if (data.employee_id.length >= 8) {

            if (data.employee_ktp.length >= 16) {

                $.ajax({
                    type : "POST",
                    url : APP_URL + "api/check-data",
                    data : data,
                    dataType : "json",
                    success : function(response){
        
                        if (response.data.role === 1) {
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
                                title: response.message,
                                showConfirmButton: false,
                                timer: 2000
                            }).then((result) => {
                                if (result.dismiss === Swal.DismissReason.timer) {
                                    location.href = APP_URL + "forgot-password/" + response.data['token'];
                                }
                              })
        
                            
                        }
                        
                    },
                    error:function(response){
                        if (!response.success) {
                        
                                $('#alert').show();
                                $('#alert').html(response.responseJSON.data.error);
                        }
                    }
                })

            } else {
                $('#employee_ktp').addClass('is-invalid');
                $('#employee_ktpFeedback').html('please input No.Ktp  min 16 char')
            }
            
        } else {
            $('#employee_id').addClass('is-invalid');
            $('#employee_idFeedback').html('please input Nik min 8 char')
        }


    });
});