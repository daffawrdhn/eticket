$(document).ready(function () {
    var token = $('#token').val()

    tableUser()


    // select regional

    $('#regional-select').on("select2:select", function(e) { 
        var regionalId = e.params.data.id
        $('#btnExport').attr('regional-id', regionalId);
        $('#employeeTable').DataTable().destroy()
        tableUser(regionalId)
    });

    // select all

    $(document).on('click','#select-all', function (e) {
        $('#btnExport').attr('regional-id', "0");


        $('#employeeTable').DataTable().destroy()
        tableUser()
        
        $("#regional-select").val(null).trigger("change"); 
        $("#start-date").val('');
        $("#end-date").val('');

    });

    function tableUser(data = null) {
        var regionalId = data == null ? 0 : data

        var datas = {
            'regionalId' : regionalId
        }

        var table = $('#employeeTable').DataTable({
            responsive: true,
            processing: true,
            autoWidth : false,
            serverSide: true,
            ajax: { 
                url: APP_URL + "api/get-user",
                type: "POST",
                data: datas,
                dataType: 'json',
                beforeSend: function(xhr, settings) { 
                    xhr.setRequestHeader('Authorization','Bearer ' + token ); 
                },
            },
            columns: [
                {data: 'employee_id', name: 'employee_id'},
                {data: 'employee_ktp', name: 'employee_ktp'},
                {data: 'employee_name', name: 'employee_name'},
                {data: 'employee_email', name: 'employee_email'}, 
                {data: 'supervisor_id', name: 'supervisor_id'}, 
                {data: 'supervisor_name', name: 'supervisor_name'}, 
                {data: 'role_name', name: 'role_name'}, 
                {data: 'organization_name', name: 'organization_name'}, 
                {data: 'regional_name', name: 'regional_name'}, 
                {
                    data: 'status', 
                    name: 'status',
                    orderable: true, 
                    searchable: true,
                },
                {
                    data: 'action', 
                    name: 'action', 
                    orderable: true, 
                    searchable: true,
                },
                
            ]   
        });
    
        return table
    }

    function IsEmail(email) { 
        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if(!regex.test(email)) {
            return false;
        }else{
            return true;
        }
    }

    $('#employee_email').keyup(function () {
        
        var employeeEmail = $(this).val()

        

       if (IsEmail(employeeEmail) == true) {
           $('#employee_email').removeClass('is-invalid');

       } else {
           $('#employee_email').addClass('is-invalid');
           $('#employee_emailFeedback').html('Email is Invalid')
       }

   })


    // // check-box

    $(document).on('click', '#master-check', function(e){    

        if($(this).is(':checked',true))  
        {
            $(".sub-check").prop('checked', true);  
        } else {  
            $(".sub-check").prop('checked',false);  
        } 
    });


    // modal post
    $(".userAdd").click(function (e) { 
        e.preventDefault();
        
        $(".modal-title").html('Add New User')
        $("#input-user").removeClass('update-user');
        $("#input-user").addClass('input-user');
        $('#employee_name').val('');
        $('#employee_ktp').val('');
        $('#employee_email').val('');
        $('#employee_birth').val('');
        $('#join_date').val('');
        $('#quit_date').val('');

        $('#regional_id option:selected').remove();
        $('#role_id option:selected').remove();
        $('#organization_id option:selected').remove();
        $('#supervisor_id option:selected').remove();
        
    });

    // export
    $(document).on('click','#btnExport', function (e) {

        var regionalId = $(this).attr('regional-id')

        data = {
            'regionalId' : regionalId,
        }

        $.ajax({
            xhrFields: {
                responseType: 'blob',
            },
            type: 'POST',
            url: APP_URL + 'api/export-report-employee',
            data: data,
            beforeSend: function(xhr, settings) { 
                xhr.setRequestHeader('Authorization','Bearer ' + token ); 
            },
            success: function(result, status, xhr) {
        
                var disposition = xhr.getResponseHeader('content-disposition');
                var matches = /"([^"]*)"/.exec(disposition);
                var filename = (matches != null && matches[1] ? matches[1] : 'employee.xlsx');
        
                // The actual download
                var blob = new Blob([result], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = filename;
        
                document.body.appendChild(link);
        
                link.click();
                document.body.removeChild(link);
            }
        });
        

    });



    


    /////////////////////////input User

    $(document).on('click', '.input-user', async function(e){
        e.preventDefault();
        
        
        var token = $('#token').val()

        data = {
            'employee_name' : $('#employee_name').val(),
            'employee_ktp' :  $('#employee_ktp').val(),
            'employee_email' : $('#employee_email').val(),
            'employee_birth' : $('#employee_birth').val(),
            'join_date' : $('#join_date').val(),
            'quit_date' : $('#quit_date').val(),
            'role_id' : $('#role_id').val(),
            'organization_id' : $('#organization_id').val(),
            'regional_id' : $('#regional_id').val(),
            'supervisor_id' : $('#supervisor_id').val(),
        }

            var date = new Date();
            var year = date.toLocaleString("default", { year: "numeric" });
            var month = date.toLocaleString("default", { month: "2-digit" });
            var day = date.toLocaleString("default", { day: "2-digit" });

            // Generate yyyy-mm-dd date string
            var formattedDate = year + "-" + month + "-" + day;
        if (IsEmail(data.employee_email) == true || data.employee_email == '') {

            if (data.employee_birth < data.join_date && data.quit_date >= formattedDate || data.employee_birth == '') {
                
                $('#employeeTable').DataTable().destroy()
                ajaxFunction("api/add-user", "POST", data, token, tableUser(), "#modalAddUser")

            }

        }
        
        
        
    });



    //////////////update data////////////////

    $(document).on('click', '.update-user', function(e){

        e.preventDefault();
        
        var token = $('#token').val()
        var id = $(this).val();

        var date = new Date();
        var year = date.toLocaleString("default", { year: "numeric" });
        var month = date.toLocaleString("default", { month: "2-digit" });
        var day = date.toLocaleString("default", { day: "2-digit" });

        // Generate yyyy-mm-dd date string
        var formattedDate = year + "-" + month + "-" + day;
    
        data = {
            'employee_name' : $('#employee_name').val(),
            'employee_ktp' :  $('#employee_ktp').val(),
            'employee_email' : $('#employee_email').val(),
            'employee_birth' : $('#employee_birth').val(),
            'join_date' : $('#join_date').val(),
            'quit_date' : $('#quit_date').val(),
            'role_id' : $('#role_id').val(),
            'organization_id' : $('#organization_id').val(),
            'regional_id' : $('#regional_id').val(),
            'supervisor_id' : $('#supervisor_id').val(),
        }
        
        $('#employeeTable').DataTable().destroy()
            ajaxFunction("api/update-user/"+ id, "PUT", data, token, tableUser(), "#modalAddUser")

        
    
    });



    //////////////RESET PASSWORD//////////////////


    $(document).on('click', '#reset-pass', function(e){
        e.preventDefault();
        
        var token = $('#token').val()
        var id = $(this).attr('data-id')

        Swal.fire({
            title: 'Are you sure?',
            text: "are you sure to reset password ??",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, do it!'
          }).then((result) => {
            if (result.isConfirmed) {
                $('#employeeTable').DataTable().destroy()
                ajaxFunction("api/reset-user-password/"+id, "POST", false, token, tableUser())
            }
        })
        
    
        
    
    });


    //////////////////////////


    //////////////DELETE DATA////////////////

    $(document).on('click', '#delete-user', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id'); 
        var contentList = $("#content-list").html();
        var token = $('#token').val()
    
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
          }).then((result) => {
            if (result.isConfirmed) {
                $('#employeeTable').DataTable().destroy()
                ajaxFunction("api/delete-user/"+  id, "DELETE", false, token, tableUser())
            }
          })
    
        
    
    });

    /////////////////////////////////////////

    



    //////////////status////////////////////
    $(document).on('click', '#status', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id'); 
        var userStatus = $(this).val(); 
        var token = $('#token').val()
        var data = { 
            status : userStatus,
            quit_date : 0,
        }
        var isStatus = userStatus == 'Active' ? 'Non Active' : 'Active'
        Swal.fire({
            title: 'Are you sure?',
            text: "Are you sure to "+ isStatus + " this user ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, do it!'
          }).then((result) => {
            if (result.isConfirmed) {
                if (userStatus == 'Active') {
                    $('#employeeTable').DataTable().destroy()
                    ajaxFunction("api/set-status-employee/" + id, "POST", data, token, tableUser())
                }else{
                    nonActiveUSer(id, userStatus)
                }
                
              
            }
          })
    
        
    
    });


    function nonActiveUSer(id, userStatus) {

        
        $("#staticBackdrop").modal('show');
        $('#new_quit_date').val('')
        $("#input-quit-date").val(id);
        $("#status").val(userStatus);

        $( "#new_quit_date" ).focus(function() {
            var date = new Date();
            var year = date.toLocaleString("default", { year: "numeric" });
            var month = date.toLocaleString("default", { month: "2-digit" });
            var day = date.toLocaleString("default", { day: "2-digit" });

            // Generate yyyy-mm-dd date string
            var formattedDate = year + "-" + month + "-" + day;

            $('#new_quit_date').attr('type', 'date');

            if ($(this).attr('type') == 'date') {

                $(this).attr('min', formattedDate)

            }
            
        })

        $( "#new_quit_date" ).focusout(function() {
            $('#new_quit_date').attr('type', 'text');

            var date = new Date();
            var year = date.toLocaleString("default", { year: "numeric" });
            var month = date.toLocaleString("default", { month: "2-digit" });
            var day = date.toLocaleString("default", { day: "2-digit" });

            // Generate yyyy-mm-dd date string
            var formattedDate = year + "-" + month + "-" + day;


            if (formattedDate > $(this).val() && $(this).val() != '') {
                $('#new_quit_date').addClass('is-invalid');
                $('#new_quit_dateFeedback').html('Please Enter the quit date > date now')
            }else{
                $('#new_quit_date').removeClass('is-invalid');
            }
        })

        
    }

    $(document).on('click', '#input-quit-date', function(e){
        e.preventDefault();
        
        var date = new Date();
        var year = date.toLocaleString("default", { year: "numeric" });
        var month = date.toLocaleString("default", { month: "2-digit" });
        var day = date.toLocaleString("default", { day: "2-digit" });

        // Generate yyyy-mm-dd date string
        var formattedDate = year + "-" + month + "-" + day;
        
        var token = $('#token').val()
        var id = $(this).val()

        data = {
            'status' : $('#status').val(),
            'quit_date' :  $('#new_quit_date').val(),
        }

        if (data.quit_date > formattedDate || data.quit_date == '') {
            $('#employeeTable').DataTable().destroy()
            
            ajaxFunction( "api/set-status-employee/"+ id, "POST", data, token, tableUser(), "#staticBackdrop")
        }
        
        
    });


});