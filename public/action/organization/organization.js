$(document).ready(function () {

   
    getDataOrganization();
    
        
    

    //getdata
    function getDataOrganization()
    {
        var token = $('#token').val()
        $.ajax({
            url : APP_URL + "api/get-organization",
            type : 'GET',
            dataType : 'json',
            beforeSend: function(xhr, settings) { 
                xhr.setRequestHeader('Authorization','Bearer ' + token ); 
            },
            success : function(response){

                    $('#table-organization').html('');
                    no = 1;
                    $(response.data).each(function(key, values){

                        $('#table-organization').append(`<tr>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input sub-check" type="checkbox" value="" id="flexCheckDefault" data-id="`+ values.organization_id +`">
                                </div>
                            </td>
                            <td>`+ no++ +`</td>
                            <td id="organization-list">`+values.organization_name+`</td>
                            <td>
                            <button type="button" id="edit-organization" class="btn btn-sm btn-success" value="`+ values.organization_id +`" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                                <button type="button" id="delete-organization" class="btn btn-danger btn-sm" value="`+ values.organization_id +`">
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

    // get organization by id
    $(document).on('click', '#edit-organization', function(e){
        e.preventDefault();
        $('#organization_name').removeClass('is-invalid');
        $(".modal-title").html('Update organization')
        $("#input-organization").removeClass('input-organization');
        $("#input-organization").addClass('update-organization');

        
        var id = $(this).val();
        

        var token = $('#token').val()
        $.ajax({
            url : APP_URL + "api/get-organization/"+ id,
            type : 'GET',
            dataType : 'json',
            beforeSend: function(xhr, settings) { 
                xhr.setRequestHeader('Authorization','Bearer ' + token ); 
            },
            success : function(response){
                $('#organization_name').val(response.data.organization_name);
                $('#input-organization').val(response.data.organization_id);
            },
            error:function(response){
                if (!response.success) {

                        console.log(response.responseJSON.data.error);
                    
                }
            }

        });
        

    });

    $(".organizationAdd").click(function (e) { 
        e.preventDefault();
        
        $(".modal-title").html('Add New organization')
        $("#input-organization").removeClass('update-organization');
        $("#input-organization").addClass('input-organization');
        $('#organization_name').val('');
        $('#organization_name').removeClass('is-invalid');
        
    });

    //post data 
    $(document).on('click', '.input-organization', function(e){
        e.preventDefault();
        
        
        var token = $('#token').val()

        data = {
            'organization_name' : $('#organization_name').val()
        }
        if (data.organization_name == '') {
            $('#organization_name').addClass('is-invalid');
            $('#organization_nameFeedback').html('please fill out this field')
        }else{
            $('#organization_name').removeClass('is-invalid');
            $.ajax({
                type : "POST",
                url : APP_URL + "api/add-organization",
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

                            $('#organization_name').val('')
                            getDataOrganization();

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

    //update organization
    $(document).on('click', '.update-organization', function(e){
        e.preventDefault();
        
        
        var token = $('#token').val()
        var id = $(this).val();

        data = {
            'organization_name' : $('#organization_name').val()
        }

        if (data.organization_name == '') {
            $('#organization_name').addClass('is-invalid');
            $('#organization_nameFeedback').html('please fill out this field')
        }else{
            $('#organization_name').removeClass('is-invalid');
            $.ajax({
                type : "POST",
                url : APP_URL + "api/update-organization/"+ id,
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
    
                            $('#organization_name').val('')
                            getDataOrganization();
    
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
    $(document).on('click', '#delete-organization', function(e){
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
                    url: APP_URL + "api/delete-organization/" + id,
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
                                    getDataOrganization();
                                } 
                            })
        
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
                            url: APP_URL + "api/delete-all-organization",
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
                                            getDataOrganization();
                                        } 
                                    })
                
                            }
                        });
                      
                    }
                  })
            
        }  
    });


});