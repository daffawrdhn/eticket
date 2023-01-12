$(document).ready(function () {

    
    

    //getdata
    var token = $('#token').val()
    var table = $('#approvalTable').DataTable({
        responsive: true,
        autoWidth : false,
        processing: true,
        serverSide: true,
        ajax: { 
            url: APP_URL + "api/get-approval",
            type: "GET",
            dataType: 'json',
            beforeSend: function(xhr, settings) { 
                xhr.setRequestHeader('Authorization','Bearer ' + token ); 
            },
        },
        columns: [
            {data: 'no', name: 'no'},
            {data: 'regional_name', name: 'regional_name'},
            {data: 'regional_name', name: 'regional_name'},
            {data: 'employee_id', name: 'employee_id'},
            {data: 'employee_name', name: 'employee_name'},
            {
                data: 'action', 
                name: 'action', 
                orderable: true, 
                searchable: true,
            },
            
        ]   
});

    // get approval by id
    $(document).on('click', '#edit-approval', function(e){
        e.preventDefault();
        
        $(".modal-title").html('Update Approval')
        $("#input-approval").removeClass('input-sub-feature');
        $("#input-approval").addClass('update-sub-feature');
        

        
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
                $('#regional_id').append(`
                    <option selected value="${response.data.feature.regional_id}">${response.data.feature['feature_name']}</option>
                `)
                $('#input-approval').val(response.data.employee_id);
            },
            error:function(response){
                if (!response.success) {

                        // console.log(response.responseJSON.data.error);
                    
                }
            }

        });
        

    });
    


    //post
    $(".approvalAdd").click(function (e) { 
        e.preventDefault();
        console.log('ok');
        $(".modal-title").html('Add New Approval')
        $("#input-approval").removeClass('update-sub-feature');
        $("#input-approval").addClass('input-sub-feature');
        $('#employee_id option:selected').remove();
        $('#regional_id option:selected').remove();
        $("div#select-regional .select2-selection--single").css('border', '1px solid #aaa');
        $("div#select-employee .select2-selection--single").css('border', '1px solid #aaa');
        $('#regional_idFeedback').hide()
        $('#employee_idFeedback').hide()
        
    });

// //post data 
    $(document).on('click', '.input-sub-feature', function(e){
        e.preventDefault();
        
        var token = $('#token').val()

        data = {
            'employee_id' : $('#employee_id').val(),
            'regional_id' : $('#regional_id').val()
        }

        if (data.employee_id == null && data.regional_id == null) {
            $(".select2-selection--single").css('border', '1px solid red');
            $('#regional_idFeedback').html('please fill out this field')
            $('#employee_idFeedback').html('please fill out this field')
            $('#regional_idFeedback').show()
            $('#employee_idFeedback').show()
            

        }else{
            if (data.employee_id == null) {
                $('#employee_idFeedback').html('please fill out this field')
                $('#employee_idFeedback').show()
                $("div#select-regional .select2-selection--single").css('border', '1px solid #aaa');
                $("div#select-employee .select2-selection--single").css('border', '1px solid red');
                $('#regional_idFeedback').hide()
                
            }else{
                if (data.regional_id == null) {
                    $("div#select-regional .select2-selection--single").css('border', '1px solid red');
                    $("div#select-employee .select2-selection--single").css('border', '1px solid #aaa');
                    $('#regional_idFeedback').html('please fill out this field')
                    $('#regional_idFeedback').show()
                    $('#employee_idFeedback').hide()
                    
                }else{
                    $(".select2-selection--single").css('border', '1px solid #aaa');
                    $('#regional_idFeedback').hide()
                    $('#employee_idFeedback').hide()

                    $.ajax({
                        type : "POST",
                        url : APP_URL + "api/add-approval",
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

                                    $("#modalAddUser").modal('hide');
        
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
                                    

                                }
                            })
                            
                        },
                        error:function(response){
                            if (!response.success) {
                                console.log(response.responseJSON.data.error);
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
            }
        }

        
    
    });

    $(document).on('click', '#edit-approval', function(e){
        e.preventDefault();
        
        $(".modal-title").html('Update Sub Feature')
        $("#input-approval").removeClass('input-approval');
        $("#input-approval").addClass('update-approval');

        
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
                $('#approval_name').val(response.data.approval_name);
                $('#input-approval').val(response.data.approval_id);
            },
            error:function(response){
                if (!response.success) {

                        console.log(response.responseJSON.data.error);
                    
                }
            }

        });
        

    });

// //update approval
$(document).on('click', '.update-sub-feature', function(e){
    e.preventDefault();
    
    
    var token = $('#token').val()
    var id = $(this).val();

    data = {
        'approval_name' : $('#approval_name').val(),
        'regional_id' : $('#regional_id').val()
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

                    $('#approval_name').val('')
                    

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
$(document).on('click', '#delete-approval', function(e){
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
                                    text : 'This data already has a relationship with the sub feature',
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
    var selectRegional =   $('#regional_id').select2({
                            placeholder : "Select Regional",
                            dropdownParent: $("#staticBackdrop"),
                            ajax: { 
                                url: APP_URL + "api/select-regional",
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
                                            text : item.regional_name,
                                            id: item.regional_id
                                        }
                                    })
                                };
                                },
                                cache: true
                            }
                        })

                    selectRegional.data('select2').$selection.css('height', '45px')
                    selectRegional.data('select2').$selection.css('padding-top', '5px')


    var selectUser =   $('#employee_id').select2({
                        placeholder : "Select Employee",
                        dropdownParent: $("#staticBackdrop"),
                        ajax: { 
                            url: APP_URL + "api/select-user",
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
                                        text : item.text,
                                        id: item.id
                                    }
                                })
                              };
                            },
                            cache: true
                        }
                    })
        
      selectUser.data('select2').$selection.css('height', '45px')
      selectUser.data('select2').$selection.css('padding-top', '5px')
    
});
