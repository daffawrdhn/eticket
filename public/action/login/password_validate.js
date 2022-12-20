function passwordValidation(){

    $('#password-field').keyup(function () {

        var pwdUpper = /[A-Z]/;
        var pwdLower = /[a-z]/;
        var pwdNumber = /[0-9]/;
        var pwdSpecial = /[!@#$%^&()'[\]"?+-/*={}.,;:_]/;

        var pwdValidation = /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{7,15}$/;
        var password = $('#password-field').val();

        // if (password.match(pwdValidation)) {
        //     $('#password-field').removeClass('is-invalid');
        // }else{
        //     $('#password-field').addClass('is-invalid');
        // }
        hoverPassword(password)
        


        if (password.length >= 8 && password.length <= 12) {
            $('#password-field').removeClass('is-invalid');
            $('#hover').removeClass('is-invalid');

            if (password.match(pwdLower) && password.match(pwdUpper)) {
                $('#hover').removeClass('is-invalid');
                $('#password-field').removeClass('is-invalid');

                if (password.match(pwdNumber)) {
                    $('#hover').removeClass('is-invalid');
                    $('#password-field').removeClass('is-invalid');

                    if (password.match(pwdSpecial)) {
                        $('#hover').removeClass('is-invalid');
                        $('#password-field').removeClass('is-invalid');
                    } else {
                        $('#password-field').addClass('is-invalid');
                        $('#hover').addClass('is-invalid');
                        $('#password-fieldFeedback').html('password at least one special character')
                    }
                } else {
                    $('#password-field').addClass('is-invalid');
                    $('#hover').addClass('is-invalid');
                    $('#password-fieldFeedback').html('password at least one number character')
                }
            } else {
                $('#password-field').addClass('is-invalid');
                $('#hover').addClass('is-invalid');
                $('#password-fieldFeedback').html('password at least one lowercase and uppercase character')
            }

        } else {
            
            $('#password-field').addClass('is-invalid');
            $('#hover').addClass('is-invalid');
            $('#password-fieldFeedback').html('password min 8 - 12 char')
        }     
        
    });


}



function hoverPassword(password) {

    if (password.length >= 8 && password.length <= 12) {
        $('#length').removeClass('text-danger');
        $('#length').addClass('text-success');
    }else{
        $('#length').addClass('text-danger');
    }




    var pwdLower = /[a-z]/;
    if (password.match(pwdLower)) {
        $('#lowerCase').removeClass('text-danger');
        $('#lowerCase').addClass('text-success');
    }else{
        $('#lowerCase').addClass('text-danger');
    }



    var pwdUpper = /[A-Z]/;
    if (password.match(pwdUpper)) {
        $('#upperCase').removeClass('text-danger');
        $('#upperCase').addClass('text-success');
    }else{
        $('#upperCase').addClass('text-danger');
    }



    var pwdNumber = /[0-9]/;
    if (password.match(pwdNumber)) {
        $('#number').removeClass('text-danger');
        $('#number').addClass('text-success');
    }else{
        $('#number').addClass('text-danger');
    }



    var pwdSpecial = /[!@#$%^&()'[\]"?+-/*={}.,;:_]/;
    if (password.match(pwdSpecial)) {
        $('#specialChar').removeClass('text-danger');
        $('#specialChar').addClass('text-success');
    }else{
        $('#specialChar').addClass('text-danger');
    }
}