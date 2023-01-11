$(document).ready(function () {

    
    getDataSubFeature();
    $('#feature_idFeedback').show()

    //getdata
    function getDataSubFeature()
    {
        var token = $('#token').val()

            $.ajax({
                url : APP_URL + "api/get-sub-feature",
                type : 'GET',
                dataType : 'json',
                beforeSend: function(xhr, settings) { 
                    xhr.setRequestHeader('Authorization','Bearer ' + token ); 
                },
                success : function(response){
    
                        $('#table-sub_feature').html('');
    
                        no = 1;
                        $(response.data).each(function(key, values){
    
                            $('#table-sub_feature').append(`<tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input sub-check" type="checkbox" value="" id="flexCheckDefault" data-id="`+ values.sub_feature_id +`">
                                    </div>
                                </td>
                                <td>`+ no++ +`</td>
                                <td id="sub_feature-list">`+values.feature.feature_name+`</td>
                                <td id="sub_feature-list">`+values.sub_feature_name+`</td>
                                <td>
                                <button type="button" id="edit-sub_feature" class="btn btn-sm btn-success" value="`+ values.sub_feature_id +`" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                    <button type="button" id="delete-sub_feature" class="btn btn-danger btn-sm" value="`+ values.sub_feature_id +`">
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
    
            })

        
    }

    // get sub_feature by id
    $(document).on('click', '#edit-sub_feature', function(e){
        e.preventDefault();
        
        $(".modal-title").html('Update Sub Feature')
        $("#input-sub_feature").removeClass('input-sub-feature');
        $("#input-sub_feature").addClass('update-sub-feature');
        

        
        var id = $(this).val();
        

        var token = $('#token').val()
        $.ajax({
            url : APP_URL + "api/get-sub-feature/"+ id,
            type : 'GET',
            dataType : 'json',
            beforeSend: function(xhr, settings) { 
                xhr.setRequestHeader('Authorization','Bearer ' + token ); 
            },
            success : function(response){
                $('#feature_id').append(`
                    <option selected value="${response.data.feature.feature_id}">${response.data.feature['feature_name']}</option>
                `)
                $('#input-sub_feature').val(response.data.sub_feature_id);
            },
            error:function(response){
                if (!response.success) {

                        console.log(response.responseJSON.data.error);
                    
                }
            }

        });
        

    });


    


    //post
    $(".sub_featureAdd").click(function (e) { 
        e.preventDefault();
        
        $(".modal-title").html('Add New sub_feature')
        $("#input-sub_feature").removeClass('update-sub-feature');
        $("#input-sub_feature").addClass('input-sub-feature');
        $('#feature_id option:selected').remove();
        $('#sub_feature_name').val('');
        $('#is-invalid').hide()
        
    });

// //post data 
    $(document).on('click', '.input-sub-feature', function(e){
        e.preventDefault();
        
        var token = $('#token').val()

        data = {
            'sub_feature_name' : $('#sub_feature_name').val(),
            'feature_id' : $('#feature_id').val()
        }
        if (data.sub_feature_name == '' && data.feature_id == '') {
            $('#sub_feature_name').addClass('is-invalid');
            $('#sub_feature_nameFeedback').html('please fill out this field')
            $('#select-feature').addClass('is-invalid');
            $('#feature_idFeedback').html('please fill out this field')
            

        }else{
            if (data.sub_feature_name == '') {
                $('#sub_feature_name').addClass('is-invalid');
                $('#sub_feature_nameFeedback').html('please fill out this field')
    
                
            }else{
                $('#sub_feature_name').removeClass('is-invalid');
            }
        }

        // $.ajax({
        //     type : "POST",
        //     url : APP_URL + "api/add-sub-feature",
        //     data : data,
        //     dataType : "json",
        //     beforeSend: function(xhr, settings) { 
        //         xhr.setRequestHeader('Authorization','Bearer ' + token ); 
        //     },
        //     success : function(response){
        //         $("#staticBackdrop").modal('hide');

        //         Swal.fire({
        //             position: 'center',
        //             icon: 'success',
        //             title: response.message,
        //             showConfirmButton: false,
        //             timer: 2000
        //         }).then((result) => {
        //             if (result.dismiss === Swal.DismissReason.timer) {

        //                 $('#sub_feature_name').val('')
        //                 getDataSubFeature();

        //             }
        //         })
                
        //     },
        //     error:function(response){
        //         if (!response.success) {

        //             if (response.responseJSON.data.error.sub_feature_name !== null) {
        //                 Swal.fire({
        //                     icon : 'warning',
        //                     confirmButtonText: 'Ok',
        //                     title : 'Warning!',
        //                     text : response.responseJSON.data.error.sub_feature_name,
                            
                            
        //                 })
        //             }else{
        //                 Swal.fire({
        //                     icon : 'warning',
        //                     confirmButtonText: 'Ok',
        //                     title : 'Warning!',
        //                     text : response.responseJSON.data.error,
                            
                            
        //                 })
        //             }
                    
        //         }
        //     }
        // })
    
    });

    $(document).on('click', '#edit-sub_feature', function(e){
        e.preventDefault();
        
        $(".modal-title").html('Update Sub Feature')
        $("#input-sub_feature").removeClass('input-sub_feature');
        $("#input-sub_feature").addClass('update-sub_feature');

        
        var id = $(this).val();
        

        var token = $('#token').val()

        
        $.ajax({
            url : APP_URL + "api/get-sub-feature/"+ id,
            type : 'GET',
            dataType : 'json',
            beforeSend: function(xhr, settings) { 
                xhr.setRequestHeader('Authorization','Bearer ' + token ); 
            },
            success : function(response){
                console.log(response);
                $('#sub_feature_name').val(response.data.sub_feature_name);
                $('#input-sub_feature').val(response.data.sub_feature_id);
            },
            error:function(response){
                if (!response.success) {

                        console.log(response.responseJSON.data.error);
                    
                }
            }

        });
        

    });

// //update sub_feature
$(document).on('click', '.update-sub-feature', function(e){
    e.preventDefault();
    
    
    var token = $('#token').val()
    var id = $(this).val();

    data = {
        'sub_feature_name' : $('#sub_feature_name').val(),
        'feature_id' : $('#feature_id').val()
    }


    $.ajax({
        type : "POST",
        url : APP_URL + "api/update-sub-feature/"+ id,
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

                    $('#sub_feature_name').val('')
                    getDataSubFeature();

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




// // delete
$(document).on('click', '#delete-sub_feature', function(e){
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
                url: APP_URL + "api/delete-sub-feature/" + id,
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
                                getDataSubFeature();
                            } 
                        })
    
                }
            });
          
        }
      })

    

});




// // check-box

$(document).on('click', '#master-check', function(e){
    

    if($(this).is(':checked',true))  
    {
        $(".sub-check").prop('checked', true);  
    } else {  
        $(".sub-check").prop('checked',false);  
    } 
});


// // delete-all
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
                        url: APP_URL + "api/delete-all-sub-feature",
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
                                        getDataSubFeature();
                                    } 
                                })
            
                        }
                    });
                  
                }
              })
        
        }  
    });

    // Select Sub Feature
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var token = $('#token').val()
    var selectFeature =   $('#feature_id').select2({
                            placeholder : "Select Feature",
                            dropdownParent: $("#staticBackdrop"),
                            ajax: { 
                                url: APP_URL + "api/select-feature",
                                type: "post",
                                dataType: 'json',
                                delay: 250,
                                beforeSend: function(xhr, settings) { 
                                    xhr.setRequestHeader('Authorization','Bearer ' + token ); 
                                },
                                data: function (params) {
                                return {
                                    _token: CSRF_TOKEN,
                                    search: params.term // search term
                                };
                                },
                                processResults: function (response) {
                                return {
                                    results: $.map(response.data, function (item) {
                                        
                                        return{
                                            text : item.feature_name,
                                            id: item.feature_id
                                        }
                                    })
                                };
                                },
                                cache: true
                            }
                        })

                    selectFeature.data('select2').$selection.css('height', '45px')
                    selectFeature.data('select2').$selection.css('padding-top', '5px')
});
