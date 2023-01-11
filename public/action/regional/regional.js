$(document).ready(function () {

   
    getDataRegional();
    
        
    

    //getdata
    function getDataRegional()
    {
        var token = $('#token').val()
        $.ajax({
            url : APP_URL + "api/get-regional",
            type : 'GET',
            dataType : 'json',
            beforeSend: function(xhr, settings) { 
                xhr.setRequestHeader('Authorization','Bearer ' + token ); 
            },
            success : function(response){

                    $('#table-regional').html('');
                    no = 1;
                    $(response.data).each(function(key, values){

                        $('#table-regional').append(`<tr>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input sub-check" type="checkbox" value="" id="flexCheckDefault" data-id="`+ values.regional_id +`">
                                </div>
                            </td>
                            <td>`+ no++ +`</td>
                            <td id="regional-list">`+values.regional_name+`</td>
                            <td>
                            <button type="button" id="edit-regional" class="btn btn-sm btn-success" value="`+ values.regional_id +`" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                                <button type="button" id="delete-regional" class="btn btn-danger btn-sm" value="`+ values.regional_id +`">
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

    // get regional by id
    $(document).on('click', '#edit-regional', function(e){
        e.preventDefault();
        $('#regional_name').removeClass('is-invalid');
        $(".modal-title").html('Update regional')
        $("#input-regional").removeClass('input-regional');
        $("#input-regional").addClass('update-regional');

        
        var id = $(this).val();
        

        var token = $('#token').val()
        $.ajax({
            url : APP_URL + "api/get-regional/"+ id,
            type : 'GET',
            dataType : 'json',
            beforeSend: function(xhr, settings) { 
                xhr.setRequestHeader('Authorization','Bearer ' + token ); 
            },
            success : function(response){
                $('#regional_name').val(response.data.regional_name);
                $('#input-regional').val(response.data.regional_id);
            },
            error:function(response){
                if (!response.success) {

                        console.log(response.responseJSON.data.error);
                    
                }
            }

        });
        

    });

    $(".regionalAdd").click(function (e) { 
        e.preventDefault();
        $('#regional_name').removeClass('is-invalid');
        $(".modal-title").html('Add New regional')
        $("#input-regional").removeClass('update-regional');
        $("#input-regional").addClass('input-regional');
        $('#regional_name').val('');

        
    });

    //post data 
    $(document).on('click', '.input-regional', function(e){
        e.preventDefault();
        
        
        var token = $('#token').val()

        data = {
            'regional_name' : $('#regional_name').val()
        }

        if (data.regional_name == '') {
            $('#regional_name').addClass('is-invalid');
            $('#regional_nameFeedback').html('please fill out this field')
        }else{
            $('#regional_name').removeClass('is-invalid');
            $.ajax({
                type : "POST",
                url : APP_URL + "api/add-regional",
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
    
                            $('#regional_name').val('')
                            getDataRegional();
    
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

    //update regional
    $(document).on('click', '.update-regional', function(e){
        e.preventDefault();
        
        
        var token = $('#token').val()
        var id = $(this).val();

        data = {
            'regional_name' : $('#regional_name').val()
        }   

        if (data.regional_name == '') {
            $('#regional_name').addClass('is-invalid');
            $('#regional_nameFeedback').html('please fill out this field')
        }else{
            $('#regional_name').removeClass('is-invalid');
            $.ajax({
                type : "POST",
                url : APP_URL + "api/update-regional/"+ id,
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
    
                            $('#regional_name').val('')
                            getDataRegional();
    
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
    $(document).on('click', '#delete-regional', function(e){
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
                    url: APP_URL + "api/delete-regional/" + id,
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
                                    getDataRegional();
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
                            url: APP_URL + "api/delete-all-regional",
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
                                            getDataRegional();
                                        } 
                                    })
                
                            }
                        });
                      
                    }
                  })
            
        }  
    });


});