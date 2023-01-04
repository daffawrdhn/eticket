$(document).ready(function () {
    
    getDataEmployee()

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

    $(document).on('click', '.input-user', function(e){
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

        
        $.ajax({
            type : "POST",
            url : APP_URL + "api/add-user",
            data : data,
            dataType : "json",
            beforeSend: function(xhr, settings) { 
                xhr.setRequestHeader('Authorization','Bearer ' + token ); 
            },
            success : function(response){
                $("#modalAddUser").modal('hide');

                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: response.message,
                    showConfirmButton: false,
                    timer: 2000
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.timer) {

                        getDataEmployee();

                    }
                    })
                
            },
            error:function(response){
                if (!response.success) {
                    Swal.fire({
                        icon : 'warning',
                        confirmButtonText: 'Ok',
                        title : 'Warning!',
                        text : response.responseJSON.data.error
                    })
                }
            }
        })
        
    });



    //////////////update data////////////////

    $(document).on('click', '.update-user', function(e){
        e.preventDefault();
        
        console.log('ok');
        
        var token = $('#token').val()
        var id = $(this).val();
    
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
            type : "POST",
            url : APP_URL + "api/update-user/"+ id,
            data : data,
            dataType : "json",
            beforeSend: function(xhr, settings) { 
                xhr.setRequestHeader('Authorization','Bearer ' + token ); 
            },
            success : function(response){
    
                $("#modalAddUser").modal('hide');
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: response.message,
                    showConfirmButton: false,
                    timer: 2000
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.timer) {
                        getDataEmployee();
    
                    }
                })
                
            },
            error:function(response){
                if (!response.success) {
    
                        Swal.fire({
                            icon : 'warning',
                            confirmButtonText: 'Ok',
                            title : 'Warning!',
                            text : response.responseJSON.data.error,
                            
                            
                        })
                    
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
                    },
                    success : function(response){
            
                        $("#modalAddUser").modal('hide');
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: response.message,
                            showConfirmButton: false,
                            timer: 2000
                        })
                        
                    },
                    error:function(response){
                        if (!response.success) {
            
                                Swal.fire({
                                    icon : 'warning',
                                    confirmButtonText: 'Ok',
                                    title : 'Warning!',
                                    text : response.responseJSON.data.error,
                                    
                                    
                                })
                            
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
                    url: APP_URL + "api/delete-user/" + id,
                    dataType: "json",
                    beforeSend: function(xhr, settings) { 
                        xhr.setRequestHeader('Authorization','Bearer ' + token ); 
                    },
                    success: function(response){
                        
                            Swal.fire({
                                icon : 'success',
                                confirmButtonText: 'Ok',
                                title : 'Deleted!',
                                text : 'Your file has been deleted.',
                                
                                
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    getDataEmployee();
                                } 
                            })
        
                    }
                });
              
            }
          })
    
        
    
    });

});