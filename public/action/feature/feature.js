$(document).ready(function () {

    getDataFeature();

    //getdata
    function getDataFeature()
    {
        var token = $('#token').val()
        $.ajax({
            url : APP_URL + "api/get-feature",
            type : 'GET',
            dataType : 'json',
            beforeSend: function(xhr, settings) { 
                xhr.setRequestHeader('Authorization','Bearer ' + token ); 
            },
            success : function(response){

                    $('#table-feature').html('');
                    no = 1;
                    $(response.data).each(function(key, values){

                        $('#table-feature').append(`<tr>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input sub-check" type="checkbox" value="" id="flexCheckDefault" data-id="`+ values.feature_id +`">
                                </div>
                            </td>
                            <td>`+ no++ +`</td>
                            <td id="feature-list">`+values.feature_name+`</td>
                            <td>
                            <button type="button" id="edit-feature" class="btn btn-sm btn-success" value="`+ values.feature_id +`" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                                <button type="button" id="delete-feature" class="btn btn-danger btn-sm" value="`+ values.feature_id +`">
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

    // get feature by id
    $(document).on('click', '#edit-feature', function(e){
        e.preventDefault();
        
        $(".modal-title").html('Update Feature')
        $("#input-feature").removeClass('input-feature');
        $("#input-feature").addClass('update-feature');

        
        var id = $(this).val();
        

        var token = $('#token').val()
        $.ajax({
            url : APP_URL + "api/get-feature/"+ id,
            type : 'GET',
            dataType : 'json',
            beforeSend: function(xhr, settings) { 
                xhr.setRequestHeader('Authorization','Bearer ' + token ); 
            },
            success : function(response){
                $('#feature_name').val(response.data.feature_name);
                $('#input-feature').val(response.data.feature_id);
            },
            error:function(response){
                if (!response.success) {

                        console.log(response.responseJSON.data.error);
                    
                }
            }

        });
        

    });

    $(".featureAdd").click(function (e) { 
        e.preventDefault();
        
        $(".modal-title").html('Add New Feature')
        $("#input-feature").removeClass('update-feature');
        $("#input-feature").addClass('input-feature');
        $('#feature_name').val('');

        
    });

    //post data 
    $(document).on('click', '.input-feature', function(e){
        e.preventDefault();
        
        
        var token = $('#token').val()

        data = {
            'feature_name' : $('#feature_name').val()
        }

        
        
        if (data.feature_name == '') {
            $('#feature_name').addClass('is-invalid');
            $('#feature_nameFeedback').html('please fill out this field')
        }else{
            $('#feature_name').removeClass('is-invalid');
            

            $.ajax({
                type : "POST",
                url : APP_URL + "api/add-feature",
                data : data,
                dataType : "json",
                beforeSend: function(xhr, settings) { 
                    xhr.setRequestHeader('Authorization','Bearer ' + token ); 
                },
                success : function(response){

    
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 2000
                    }).then((result) => {
                        if (result.dismiss === Swal.DismissReason.timer) {

                            $('#feature_name').val('')
                            getDataFeature();

                        }
                      })
                    
                },
                error:function(response){
                    if (!response.success) {
                            console.log(response.responseJSON.data.error);
                    }
                }
            })
        }
    });

    //update feature
    $(document).on('click', '.update-feature', function(e){
        e.preventDefault();
        
        
        var token = $('#token').val()
        var id = $(this).val();

        data = {
            'feature_name' : $('#feature_name').val()
        }

        
        
        if (data.feature_name == '') {
            $('#feature_name').addClass('is-invalid');
            $('#feature_nameFeedback').html('please fill out this field')
        }else{
            $('#feature_name').removeClass('is-invalid');
            

            $.ajax({
                type : "POST",
                url : APP_URL + "api/update-feature/"+ id,
                data : data,
                dataType : "json",
                beforeSend: function(xhr, settings) { 
                    xhr.setRequestHeader('Authorization','Bearer ' + token ); 
                },
                success : function(response){

    
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 2000
                    }).then((result) => {
                        if (result.dismiss === Swal.DismissReason.timer) {

                            $('#feature_name').val('')
                            getDataFeature();

                        }
                      })
                    
                },
                error:function(response){
                    if (!response.success) {
                            console.log(response.responseJSON.data.error);
                        
                    }
                }
            })
        }
    });




    // delete
    $(document).on('click', '#delete-feature', function(e){
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
                    url: APP_URL + "api/delete-feature/" + id,
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
                                    getDataFeature();
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
                            url: APP_URL + "api/delete-all-feature",
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
                                            getDataFeature();
                                        } 
                                    })
                
                            }
                        });
                      
                    }
                  })
            
        }  
    });


});