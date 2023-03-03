$(document).ready(function () {
    $('#alert').hide();

    $("#password-field").keyup(function (e) { 
        var passwordInputId = '#password-field'
        var divHoverId = '#hover'
        var classElement = 'is-invalid'
        var passFieldFeedBackId =  '#password-fieldFeedback'
        var password = $(this).val()
        var validation = new Validation
        var passValidation = validation.passwordValidation(password)
        validation.passwordDescription(password)

        if (passValidation.validation == true) {
            $(divHoverId).removeClass(classElement);
            $(passwordInputId).removeClass(classElement);
        }else{
            $(passwordInputId).addClass(classElement);
            $(divHoverId).addClass(classElement);
            $(passFieldFeedBackId).html(passValidation.message)
        }
    });


    $('#employee_id').keyup(function () {
        
        var employeeId = $(this).val()
        var validation = new Validation
        validation.employeeIdValidation(employeeId)

   })

    
    //login
    $('#form-login').submit(function (e) { 
        e.preventDefault();
        var validation = new Validation
        var employeeId = $('#employee_id').val()
        var password = $('#password-field').val()
        var isEmployeeIdValidate = validation.employeeIdValidation(employeeId)
        var isPasswordValidate = validation.passwordValidation(password)
        var data = {
            'employee_id' : employeeId,
            'password' : password
        }


        if (isEmployeeIdValidate == true && isPasswordValidate.validation == true) {
            var ajax = new Ajax(APP_URL + "api/login", data, "POST")
            ajax.loginAjaxFunction("dashboard")
            
        }
    })

    $("#logout").click(function () {
        window.location.href = APP_URL + "logout";
    })
})