$(document).ready(function () {
        
    

    //getdata
    var token = $('#token').val()
    var table = $('#roleTable').DataTable({
        responsive: true,
        autoWidth : false,
        processing: true,
        serverSide: true,
        ajax: { 
            url: APP_URL + "api/get-role-datatable",
            type: "GET",
            dataType: 'json',
            beforeSend: function(xhr, settings) { 
                xhr.setRequestHeader('Authorization','Bearer ' + token ); 
            },
        },
        columns: [
            {data: 'no', name: 'no'},
            {data: 'role_name', name: 'role_name'},
            {
                data: 'action', 
                name: 'action', 
                orderable: true, 
                searchable: true,
            },
            
        ]   
    });

    // get role by id
    $(document).on('click', '#edit-role', function(e){
        e.preventDefault();
        $('#role_name').removeClass('is-invalid');
        $(".modal-title").html('Update role')
        $("#input-role").removeClass('input-role');
        $("#input-role").addClass('update-role');

        
        var id = $(this).val();
        

        var token = $('#token').val()
        $.ajax({
            url : APP_URL + "api/get-role/"+ id,
            type : 'GET',
            dataType : 'json',
            beforeSend: function(xhr, settings) { 
                xhr.setRequestHeader('Authorization','Bearer ' + token ); 
            },
            success : function(response){
                $('#role_name').val(response.data.role_name);
                $('#input-role').val(response.data.role_id);
            },
            error:function(response){
                if (!response.success) {

                        console.log(response.responseJSON.data.error);
                    
                }
            }

        });
        

    });

    $(".roleAdd").click(function (e) { 
        e.preventDefault();
        
        $(".modal-title").html('Add New role')
        $("#input-role").removeClass('update-role');
        $("#input-role").addClass('input-role');
        $('#role_name').val('');
        $('#role_name').removeClass('is-invalid');
        
    });

    //post data 
    $(document).on('click', '.input-role', function(e){
        e.preventDefault();
        
        $('#role_name').removeClass('is-invalid');
        var token = $('#token').val()

        data = {
            'role_name' : $('#role_name').val()
        }

        if (data.role_name == '') {
            $('#role_name').addClass('is-invalid');
            $('#role_nameFeedback').html('please fill out this field')
        }else{
            $('#role_name').removeClass('is-invalid');

            $.ajax({
                type : "POST",
                url : APP_URL + "api/add-role",
                data : data,
                dataType : "json",
                beforeSend: function(xhr, settings) { 
                    xhr.setRequestHeader('Authorization','Bearer ' + token ); 
                },
                success : function(response){
                    $("#staticBackdrop").modal('hide');
    
                    modalSuccess(response.message, table)
                    
                },
                error:function(response){
                    if (!response.success) {
                        modalError(response.responseJSON.data.error)
                    }
                }
            })
        }

        
        
        
    });

    //update role
    $(document).on('click', '.update-role', function(e){
        e.preventDefault();
        
        
        var token = $('#token').val()
        var id = $(this).val();

        data = {
            'role_name' : $('#role_name').val()
        }
        if (data.role_name == '') {
            $('#role_name').addClass('is-invalid');
            $('#role_nameFeedback').html('please fill out this field')
        }else{
            $('#role_name').removeClass('is-invalid');

            $.ajax({
                type : "POST",
                url : APP_URL + "api/update-role/"+ id,
                data : data,
                dataType : "json",
                beforeSend: function(xhr, settings) { 
                    xhr.setRequestHeader('Authorization','Bearer ' + token ); 
                },
                success : function(response){
    
                    $("#staticBackdrop").modal('hide');
                    modalSuccess(response.message, table)
                    
                },
                error:function(response){
                    if (!response.success) {
                        modalError(response.responseJSON.data.error)
                    }
                }
            })
        }

        
    
    });




    // delete
    $(document).on('click', '#delete-role', function(e){
        e.preventDefault();
        var id = $(this).val(); 
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
                    url: APP_URL + "api/delete-role/" + id,
                    dataType: "json",
                    beforeSend: function(xhr, settings) { 
                        xhr.setRequestHeader('Authorization','Bearer ' + token ); 
                    },
                    success: function(response){
                        
                            modalSuccess(response.message, table)
        
                    },error:function(response){
                        if (!response.success) {
                                console.log(response.responseJSON.data.error);

                                if (response.responseJSON.data.error.errorInfo[1]  == 7) {
                                    var text = 'This data already has a relationship with the user'
                                    
                                    modalError(text)
                                }else{

                                    modalError(response.responseJSON.data.error)
                                }
                            
                            
                        }
                    }
                });
              
            }
          })

        

    });




    // check-box

    $(document).on('click', '#master-check', function(e){
        

        if($(this).is(':checked',true))  
        {
            $(".sub-check").prop('checked', true);  
        } else {  
            $(".sub-check").prop('checked',false);  
        } 
    });


    // delete-all
    $('#delete-all').on('click', function(e) {

        var allVals = [];  
        $(".sub-check:checked").each(function() {  
            allVals.push($(this).attr('data-id'));
        });  
        if(allVals.length <=0)  
        {  
            Swal.fire({
                icon : 'warning',
                confirmButtonText: 'Ok',
                title : 'Warning!',
                text : 'Please select row!!',
            })

        }  else {  
            
                var join_selected_values = allVals.join(","); 
                var token = $('#token').val()
                var data = {'ids' : join_selected_values}

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
                            url: APP_URL + "api/delete-all-role",
                            dataType: "json",
                            data : data,
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
                                            $("#master-check").prop('checked', false); 
                                            getDataRole();
                                        } 
                                    })
                
                            }
                        });
                      
                    }
                  })
            
        }  
    });


});