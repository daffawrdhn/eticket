$(document).ready(function () {

   
    getDataRole();
    
        
    

    //getdata
    function getDataRole()
    {
        var token = $('#token').val()
        $.ajax({
            url : APP_URL + "api/get-role",
            type : 'GET',
            dataType : 'json',
            beforeSend: function(xhr, settings) { 
                xhr.setRequestHeader('Authorization','Bearer ' + token ); 
            },
            success : function(response){

                    $('#table-role').html('');
                    no = 1;
                    $(response.data).each(function(key, values){

                        $('#table-role').append(`<tr>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input sub-check" type="checkbox" value="" id="flexCheckDefault" data-id="`+ values.role_id +`">
                                </div>
                            </td>
                            <td>`+ no++ +`</td>
                            <td id="role-list">`+values.role_name+`</td>
                            <td>
                            <button type="button" id="edit-role" class="btn btn-sm btn-success" value="`+ values.role_id +`" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                                <button type="button" id="delete-role" class="btn btn-danger btn-sm" value="`+ values.role_id +`">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </td>
                        </tr>`);
                    })
                

            },
            error:function(response){
                if (!response.success) {
                        // $('#alert').show();
                        // $('#alert').html(response.responseJSON.data.error);


                        console.log(response.responseJSON.data.error);
                    
                }
            }

        });
    }

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
    
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 2000
                    }).then((result) => {
                        if (result.dismiss === Swal.DismissReason.timer) {
    
                            $('#role_name').val('')
                            getDataRole();
    
                        }
                        })
                    
                },
                error:function(response){
                    if (!response.success) {
                        Swal.fire({
                            icon : 'warning',
                            confirmButtonText: 'Ok',
                            title : 'Warning!',
                            text : "please fill out this field",
                            
                            
                        })
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
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 2000
                    }).then((result) => {
                        if (result.dismiss === Swal.DismissReason.timer) {
    
                            $('#role_name').val('')
                            getDataRole();
    
                        }
                        })
                    
                },
                error:function(response){
                    if (!response.success) {
                        Swal.fire({
                            icon : 'warning',
                            confirmButtonText: 'Ok',
                            title : 'Warning!',
                            text : "please fill out this field",
                            
                            
                        })
                        
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
                        
                            Swal.fire({
                                icon : 'success',
                                confirmButtonText: 'Ok',
                                title : 'Deleted!',
                                text : 'Your file has been deleted.',
                                
                                
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    getDataRole();
                                } 
                            })
        
                    },error:function(response){
                        if (!response.success) {
                                console.log(response.responseJSON.data.error);

                                if (response.responseJSON.data.error.errorInfo[1]  == 7) {
                                    Swal.fire({
                                        icon : 'warning',
                                        confirmButtonText: 'Ok',
                                        title : 'Warning!',
                                        text : 'This data already has a relationship with the user',
                                    })
                                }else{

                                    Swal.fire({
                                        icon : 'warning',
                                        confirmButtonText: 'Ok',
                                        title : 'Warning!',
                                        text : response.responseJSON.data.error,
                                        
                                        
                                    })
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