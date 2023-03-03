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
                var ajax = new Ajax(APP_URL + "api/check-data", data, "POST")
                ajax.loginAjaxFunction("forgot-password/")

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