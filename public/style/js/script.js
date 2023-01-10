window.addEventListener('DOMContentLoaded', event => {

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        //     document.body.classList.toggle('sb-sidenav-toggled');
        // }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

});


$(document).ready(function() {
    $("#show_hide_password a").on('click', function(event) {
        event.preventDefault();
        if($('#show_hide_password input').attr("type") == "text"){
            $('#show_hide_password input').attr('type', 'password');
            $('#show_hide_password i').addClass( "bi bi-eye-slash" );
            $('#show_hide_password i').removeClass( "bi bi-eye" );
        }else if($('#show_hide_password input').attr("type") == "password"){
            $('#show_hide_password input').attr('type', 'text');
            $('#show_hide_password i').removeClass( "bi bi-eye-slash" );
            $('#show_hide_password i').addClass( "bi bi-eye" );
        }
    });

    $("#show-hide-confirm a").on('click', function(event) {
        event.preventDefault();
        if($('#show-hide-confirm input').attr("type") == "text"){
            $('#show-hide-confirm input').attr('type', 'password');
            $('#show-hide-confirm i').addClass( "bi bi-eye-slash" );
            $('#show-hide-confirm i').removeClass( "bi bi-eye" );
        }else if($('#show-hide-confirm input').attr("type") == "password"){
            $('#show-hide-confirm input').attr('type', 'text');
            $('#show-hide-confirm i').removeClass( "bi bi-eye-slash" );
            $('#show-hide-confirm i').addClass( "bi bi-eye" );
        }
    });

    $( "div.description" ).hover(
        function() {
            var className = $('#hover').attr('class');

            if (className == "is-invalid") {
                $('#pswd_info').show();
                $('#pswd_desc').show();
            }
        }, function() {
            $('#pswd_info').hide();
            $('#pswd_desc').hide();
        }
      );
       
        $( "#employee_birth" ).focus(function() {

            var joinDate = $('#join_date').val()
            $('#employee_birth').attr('type', 'date');

            if ($(this).attr('type') == 'date') {

                $(this).attr('max', joinDate)

            }
            
        })

        $( "#employee_birth" ).focusout(function() {
            var joinDate = $('#join_date').val()
            $('#employee_birth').attr('type', 'text');

            if (joinDate < $(this).val() && joinDate != '') {
                $('#employee_birth').addClass('is-invalid');
                $('#employee_birthFeedback').html('Please Enter the date of birth < join date')
            }else{
                $('#employee_birth').removeClass('is-invalid');
                $('#join_date').removeClass('is-invalid');
            }
        })

        $( "#join_date" ).focus(function() {            
            $('#join_date').attr('type', 'date');
            var minDate = $('#employee_birth').val()
            if ($(this).attr('type') == 'date') {

                $(this).attr('min', minDate)

            }
        })


        $( "#join_date" ).focusout(function() {
            $('#join_date').attr('type', 'text');
            var isBirth = $('#employee_birth').val()

            if (isBirth > $(this).val() && isBirth != '') {
                $('#join_date').addClass('is-invalid');
                $('#join_dateFeedback').html('Please Enter the join date > date of birth')
            }else{
                $('#join_date').removeClass('is-invalid');
                $('#employee_birth').removeClass('is-invalid');
            }
        })

        $( "#quit_date" ).focus(function() {
            var date = new Date();
            var year = date.toLocaleString("default", { year: "numeric" });
            var month = date.toLocaleString("default", { month: "2-digit" });
            var day = date.toLocaleString("default", { day: "2-digit" });

            // Generate yyyy-mm-dd date string
            var formattedDate = year + "-" + month + "-" + day;

            $('#quit_date').attr('type', 'date');

            if ($(this).attr('type') == 'date') {

                $(this).attr('min', formattedDate)

            }
            
        })

        $( "#quit_date" ).focusout(function() {
            $('#quit_date').attr('type', 'text');

            var date = new Date();
            var year = date.toLocaleString("default", { year: "numeric" });
            var month = date.toLocaleString("default", { month: "2-digit" });
            var day = date.toLocaleString("default", { day: "2-digit" });

            // Generate yyyy-mm-dd date string
            var formattedDate = year + "-" + month + "-" + day;


            if (formattedDate > $(this).val() && $(this).val() != '') {
                $('#quit_date').addClass('is-invalid');
                $('#quit_dateFeedback').html('Please Enter the quit date > date now')
            }else{
                $('#quit_date').removeClass('is-invalid');
            }
        })
});

