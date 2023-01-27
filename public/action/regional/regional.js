$(document).ready(function () {

   
    var token = $('#token').val()
    var table = $('#regionalTable').DataTable({
        responsive: true,
        autoWidth : false,
        processing: true,
        serverSide: true,
        ajax: { 
            url: APP_URL + "api/get-regional-datatable",
            type: "GET",
            dataType: 'json',
            beforeSend: function(xhr, settings) { 
                xhr.setRequestHeader('Authorization','Bearer ' + token ); 
            },
        },
        columns: [
            {data: 'no', name: 'no'},
            {data: 'regional_name', name: 'regional_name'},
            {
                data: 'action', 
                name: 'action', 
                orderable: true, 
                searchable: true,
            },
            
        ]   
    });

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
    $(document).on('click', '#delete-regional', function(e){
        e.preventDefault();
        var id = $(this).val(); 
        var contentList = $("#content-list").html();
        var token = $('#token').val()
        console.log(id);
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
                        
                            modalSuccess(response.message)
        
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