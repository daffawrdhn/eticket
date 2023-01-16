$(document).ready(function () {

    
    var token = $('#token').val()
    var table = $('#featureTable').DataTable({
        responsive: true,
        autoWidth : false,
        processing: true,
        serverSide: true,
        ajax: { 
            url: APP_URL + "api/get-feature-table",
            type: "GET",
            dataType: 'json',
            beforeSend: function(xhr, settings) { 
                xhr.setRequestHeader('Authorization','Bearer ' + token ); 
            },
        },
        columns: [
            {data: 'no', name: 'no'},
            {data: 'feature_name', name: 'feature_name'},
            {
                data: 'action', 
                name: 'action', 
                orderable: true, 
                searchable: true,
            },
            
        ]   
    });


    // get feature by id
    $(document).on('click', '#edit-feature', function(e){
        e.preventDefault();
        $('#feature_name').removeClass('is-invalid');
        $(".modal-title").html('Update Jenis Tiket')
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
        $('#feature_name').removeClass('is-invalid');
        $(".modal-title").html('Add New Jenis Tiket')
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

        console.log(data);
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
                        
    
                        $("#staticBackdrop").modal('hide');
                        setTimeout(()=>{
                                    
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: response.message,
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
    
                        $("#staticBackdrop").modal('hide');
                        setTimeout(()=>{
                                    
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: response.message,
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
                    url: APP_URL + "api/delete-feature/"+id,
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
                            Swal.fire({ 
                                icon : 'warning',
                                confirmButtonText: 'Ok',
                                title : 'Warning!',
                                text : response.responseJSON.data.error,
                            })
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
                                            $("#master-check").prop('checked', false); 
                                        
                                        } 
                                    })
                
                            }
                        });
                      
                    }
                  })
            
        }  
    });


});