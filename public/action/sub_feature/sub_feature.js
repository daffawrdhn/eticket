$(document).ready(function () {

    
    var token = $('#token').val()
    var table = $('#subFeatureTable').DataTable({
        responsive: true,
        autoWidth : false,
        processing: true,
        serverSide: true,
        ajax: { 
            url: APP_URL + "api/get-sub-feature-table",
            type: "GET",
            dataType: 'json',
            beforeSend: function(xhr, settings) { 
                xhr.setRequestHeader('Authorization','Bearer ' + token ); 
            },
        },
        columns: [
            {data: 'no', name: 'no'},
            {data: 'feature_name', name: 'feature_name'},
            {data: 'sub_feature_name', name: 'sub_feature_name'},
            {
                data: 'action', 
                name: 'action', 
                orderable: true, 
                searchable: true,
            },
            
        ]   
    });

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

                        // console.log(response.responseJSON.data.error);
                    
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
        $('#sub_feature_name').removeClass('is-invalid');
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

        if (data.sub_feature_name == '' && data.feature_id == null) {
            
            $('#sub_feature_name').addClass('is-invalid');
            $('#sub_feature_nameFeedback').html('please fill out this field')
            $(".select2-selection--single").css('border', '1px solid red');
            $('#feature_idFeedback').html('please fill out this field')
            

        }else{
            if (data.sub_feature_name == '') {
                $('#sub_feature_name').addClass('is-invalid');
                $('#sub_feature_nameFeedback').html('please fill out this field')
                $(".select2-selection--single").css('border', '1px solid #aaa');
                $('#feature_idFeedback').html('')
                
            }else{
                if (data.feature_id == null) {
                    $(".select2-selection--single").css('border', '1px solid red');
                    $('#feature_idFeedback').html('please fill out this field')
        
                    
                }else{
                    $(".select2-selection--single").css('border', '1px solid #aaa');
                    $('#feature_idFeedback').html('')
                    $('#sub_feature_name').removeClass('is-invalid');

                    $.ajax({
                        type : "POST",
                        url : APP_URL + "api/add-sub-feature",
                        data : data,
                        dataType : "json",
                        beforeSend: function(xhr, settings) { 
                            xhr.setRequestHeader('Authorization','Bearer ' + token ); 
                        },
                        success : function(response){
                            $("#staticBackdrop").modal('hide');

                            setTimeout(()=>{
                                    
                                modalSuccess(response.message, table)
                            },1000)
                            
                        },
                        error:function(response){
                            if (!response.success) {
                                
                                if (response.responseJSON.data.error.sub_feature_name !== null) {
                                    var text = response.responseJSON.data.error.sub_feature_name
                                    modalError(text)
                                }else{
                                    modalError(response.responseJSON.data.error)
                                }
                                
                            }
                        }
                    })
                                
                }
            }
        }

        
    
    });

    $(document).on('click', '#edit-sub_feature', function(e){
        e.preventDefault();
        
        $(".modal-title").html('Update Sub Feature')
        $("#input-sub_feature").removeClass('input-sub_feature');
        $("#input-sub_feature").addClass('update-sub_feature');
        $('#sub_feature_name').removeClass('is-invalid');
        
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

    if (data.sub_feature_name == '') {
        $('#sub_feature_name').addClass('is-invalid');
        $('#sub_feature_nameFeedback').html('please fill out this field')
    }else{

        $.ajax({
            type : "POST",
            url : APP_URL + "api/update-sub-feature/"+ id,
            data : data,
            dataType : "json",
            beforeSend: function(xhr, settings) { 
                xhr.setRequestHeader('Authorization','Bearer ' + token ); 
            },
            success : function(response){
                $('#sub_feature_name').removeClass('is-invalid');
                $("#staticBackdrop").modal('hide');
                setTimeout(()=>{
                                        
                    modalSuccess(response.message, table)

                },1000)
                
            },
            error:function(response){
                if (!response.success) {
                    modalError(response.responseJSON.data.error)
                }
            }
        })
    }


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
                    setTimeout(()=>{
                                    
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Deleted!',
                            text : 'Your file has been deleted.',
                            showConfirmButton: false,
                            timer: 2000,
                            willClose: () => {
                                table.draw()
                            }
                        }).then((result) => {
                            if (result.dismiss === Swal.DismissReason.timer) {
                                
                                table.draw()
                                
            
                            }
                        })
                    },1000)
                    
    
                },error:function(response){
                    if (!response.success) {
                            console.log(response.responseJSON.data.error);

                            if (response.responseJSON.data.error.errorInfo[1]  == 7) {
                                Swal.fire({
                                    icon : 'warning',
                                    confirmButtonText: 'Ok',
                                    title : 'Warning!',
                                    text : 'This data already has a relationship in another table',
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
