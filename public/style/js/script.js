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
            $('#employee_birth').attr('type', 'date');
            
        })

        $( "#employee_birth" ).focusout(function() {
            $('#employee_birth').attr('type', 'text');
        })



        // test data table


        $('#subFeatureTable').DataTable()

});



