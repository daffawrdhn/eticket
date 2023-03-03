class Validation{


    passwordValidation(password){
        var validation
        var isValidation
        var message
        var pwdUpper = /[A-Z]/;
        var pwdLower = /[a-z]/;
        var pwdNumber = /[0-9]/;
        var pwdSpecial = /[!@#$%^&()'[\]"?+-/*={}.,;:_]/;
        var pwdValidation = /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{7,15}$/;

        if (password.match(pwdValidation)) {
            validation = true
            message = ''
        }else{
            if (password.length >= 8 && password.length <= 12) {
    
                if (password.match(pwdLower) && password.match(pwdUpper)) {
    
                    if (password.match(pwdNumber)) {
    
                        if (password.match(pwdSpecial)) {
                            validation = true
                            message = ''
    
                        } else {
                            validation = false
                            message = 'password at least one special character'
                        }
                    } else {
                        validation = false
                        message = 'password at least one number character'
                    }
                } else {
                    validation = false
                    message = 'password at least one lowercase and uppercase character'
                }
    
            } else {
                validation = false
                message = 'password min 8 - 12 char'
            }
        }
        

        isValidation = {
            'validation' : validation,
            'message' : message,
        }

        return isValidation

    }


    employeeIdValidation(employeeId){
        if (employeeId.length >= 8) {
            $('#employee_id').removeClass('is-invalid');

            return true
        } else {
            $('#employee_id').addClass('is-invalid');
            $('#employee_idFeedback').html('NIk min 8 - 12 char')


            return false
        }
    }


    passwordDescription(password){

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



    emailValidation(email){
        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if(!regex.test(email)) {
            return false;
        }else{
            return true;
        }
    }





}