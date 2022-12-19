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

        

        console.log('ok');

        if (password.length >= 8 && password.length <= 12) {
            $('#password-field').removeClass('is-invalid');

            if (password.match(pwdLower) && password.match(pwdUpper)) {
                $('#password-field').removeClass('is-invalid');

                if (password.match(pwdNumber)) {
                    $('#password-field').removeClass('is-invalid');

                    if (password.match(pwdSpecial)) {
                        $('#password-field').removeClass('is-invalid');
                    } else {
                        $('#password-field').addClass('is-invalid');
                        $('#validation-password').html('password at least one special character')
                    }
                } else {
                    $('#password-field').addClass('is-invalid');
                    $('#validation-password').html('password at least one number character')
                }
            } else {
                $('#password-field').addClass('is-invalid');
                $('#validation-password').html('password at least one lowercase and uppercase character')
            }

        } else {
            
            $('#password-field').addClass('is-invalid');
            $('#validation-password').html('password min 8 - 12 char')
        }     
        
    });


}