$(document).ready(function () {
    var token = $('#token').val()
    var table = $('#employeeTable').DataTable({
                responsive: true,
                processing: true,
                autoWidth : false,
                serverSide: true,
                ajax: { 
                    url: APP_URL + "api/get-user",
                    type: "GET",
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
                $.ajax({
                    type : "POST",
                    url : APP_URL + "api/add-user",
                    data : data,
                    dataType : "json",
                    beforeSend: function(xhr, settings) { 
                        xhr.setRequestHeader('Authorization','Bearer ' + token );
                        
                        $("#loading").modal('show')
                        
                    },
                    success : function(response){
                        $("#modalAddUser").modal('hide');
        
                        setTimeout(()=>{
                            $("#loading").modal('hide')
                            modalSuccess(response.message, table)
                        },1000)
                        
                    },
                    error:function(response){
                        if (!response.success) {
                            console.log(response.responseJSON.data.error);
                            setTimeout(() => {
                                $("#loading").modal('hide');
                                Swal.fire({
                                    icon : 'warning',
                                    confirmButtonText: 'Ok',
                                    title : 'Warning!',
                                    html : '<ul></ul>',
                                    didOpen: () => {
                                        const ul = Swal.getHtmlContainer().querySelector('ul')
                                        $.each(response.responseJSON.data.error, function (key, value) { 
                                             $(ul).append('<li>'+ value +'</li>');
                                        });
                                      },
                                })
                            },500)
                            
                            
                        }
                    }
                })
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
    
        
            $.ajax({
                type : "PUT",
                url : APP_URL + "api/update-user/"+ id,
                data : data,
                dataType : "json",
                beforeSend: function(xhr, settings) { 
                    xhr.setRequestHeader('Authorization','Bearer ' + token ); 
                    $("#loading").modal('show')
                },
                success : function(response){
                    
                    $("#modalAddUser").modal('hide');
                    setTimeout(()=>{
                        $("#loading").modal('hide') 
                            modalSuccess(response.message, table)
                        
                    },1000)
                    
                    
                    
                },
                error:function(response){
                    
                    if (!response.success) {
                    
                        setTimeout(() => {
                            $("#loading").modal('hide');
                            Swal.fire({
                                icon : 'warning',
                                confirmButtonText: 'Ok',
                                title : 'Warning!',
                                html : '<ul></ul>',
                                didOpen: () => {
                                    const ul = Swal.getHtmlContainer().querySelector('ul')
                                    $.each(response.responseJSON.data.error, function (key, value) { 
                                         $(ul).append('<li>'+ value +'</li>');
                                    });
                                  },
                            })
                        },500)

                        
                    }
                }
            })
        
    
    });



    //////////////RESET PASSWORD//////////////////


    $(document).on('click', '#reset-pass', function(e){
        e.preventDefault();
        
        console.log('ok');
        
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
                $.ajax({
                    type : "POST",
                    url : APP_URL + "api/reset-user-password/"+ id,
                    dataType : "json",
                    beforeSend: function(xhr, settings) { 
                        xhr.setRequestHeader('Authorization','Bearer ' + token );
                        $("#loading").modal('show') 
                    },
                    success : function(response){
                        $("#modalAddUser").modal('hide');
                        setTimeout(() => { 
                            $("#loading").modal('hide') 
                            modalSuccess(response.message, table)
                         },1000)
                    },
                    error:function(response){
                        if (!response.success) {
            
                            setTimeout(() => { 
                                $("#loading").modal('hide');
                                modalError(response.responseJSON.data.error)
                                
                             },1000)
                            
                        }
                    }
                })

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
                $.ajax({
                    type: "DELETE",
                    url: APP_URL + "api/delete-user/"+  id,
                    dataType: "json",
                    beforeSend: function(xhr, settings) { 
                        xhr.setRequestHeader('Authorization','Bearer ' + token );
                        $("#loading").modal('show')
                    },
                    success: function(response){
                    
                            setTimeout(()=>{

                                $("#loading").modal('hide') 

                                modalSuccess(response.message, table)
                            },1000)
                        
                    },error:function(response){
                        if (!response.success) {
                                if (response.responseJSON.data.error.errorInfo[1]  == null) {
                                    setTimeout(() =>{
                                        $("#loading").modal('hide') 
                                        modalError(response.responseJSON.data.error)
                                        
                                    },1000)
                                }else{
                                    setTimeout(() =>{
                                        $("#loading").modal('hide') 
                                        var text = 'This data already has a relationship with Another Table'
                                        modalError(text)
                                    },1000)
                                }
                            
                        }
                    }
                });
              
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
                    $.ajax({
                        type: "POST",
                        url: APP_URL + "api/set-status-employee/" + id,
                        data : { 
                            status : userStatus,
                            quit_date : 0,
                        },
                        dataType: "json",
                        beforeSend: function(xhr, settings) { 
                            xhr.setRequestHeader('Authorization','Bearer ' + token ); 
                            $("#loading").modal('show') 
                        },
                        success: function(response){
                            
                            setTimeout(()=>{
                                $("#loading").modal('hide') 
                                modalSuccess(response.message, table)
                            },1000)
            
                        }
                    });
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
            $.ajax({
                type : "POST",
                url : APP_URL + "api/set-status-employee/"+ id,
                data : data,
                dataType : "json",
                beforeSend: function(xhr, settings) { 
                    xhr.setRequestHeader('Authorization','Bearer ' + token ); 
                    $("#loading").modal('show') 
                },
                success : function(response){
                    
                    $("#staticBackdrop").modal('hide');
    

                    setTimeout(()=>{
                        $("#loading").modal('hide') 
                        modalSuccess(response.message, table)
                    },1000)
                    
                },
                error:function(response){
                    if (!response.success) {
                        setTimeout(()=>{
                            $("#loading").modal('hide') 
                            modalError(response.responseJSON.data.error)
                        },1000)
                        
                    }
                }
            })
        }
        
        
        
    });


    $(document).on('click','#btnExport', function (e) {

        fnExcelReport()

        // $("#summaryTable").table2excel({
        //     exclude:".noExl",
        //     filename: "ticketSummary.xls"
        // });

    });

    function fnExcelReport()
    {
        var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
        var textRange; 
        var j=0;
        tab = document.getElementById('employeeTable'); // id of table
        console.log(tab);
        for(j = 0 ; j < tab.rows.length ; j++) 
        {     
            tab_text=tab_text+tab.rows[j].innerHTML+"hallo</tr>";
            //tab_text=tab_text+"</tr>";
        }
    
        tab_text=tab_text+"</table>";
        tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
        tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
        tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params
    
        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE "); 
    
        if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
        {
            txtArea1.document.open("txt/html","replace");
            txtArea1.document.write(tab_text);
            txtArea1.document.close();
            txtArea1.focus(); 
            sa=txtArea1.document.execCommand("SaveAs",true,"Say Thanks to Sumit.xls");
        }  
        else                 //other browser not tested on IE 11
            sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));  
    
        return (sa);
    }


});