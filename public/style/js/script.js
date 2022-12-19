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

    $( "div.input-group" ).hover(
        function() {
            var className = $('#hover').attr('class');

            console.log(className);

            if (className == "is-invalid") {
                $('#pswd_info').show();
            }
        }, function() {
            $('#pswd_info').hide();
        }
      );
       
      $( "li.fade" ).hover(function() {
        $( this ).fadeOut( 100 );
        $( this ).fadeIn( 500 );
      });

});



