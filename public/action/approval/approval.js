$(document).ready(function () {

    
    

    //getdata
    var token = $('#token').val()
    var table = $('#approvalTable').DataTable({
        responsive: true,
        autoWidth : false,
        processing: true,
        serverSide: true,
        ajax: { 
            url: APP_URL + "api/get-regional-pic",
            type: "GET",
            dataType: 'json',
            beforeSend: function(xhr, settings) { 
                xhr.setRequestHeader('Authorization','Bearer ' + token ); 
            },
        },
        columns: [
            {data: 'no', name: 'no'},
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


    


    //post
    $(".approvalAdd").click(function (e) { 
        e.preventDefault();
        console.log('ok');
        $(".modal-title").html('Add New Approval')
        $("#input-approval").removeClass('update-approval');
        $("#input-approval").addClass('input-approval');
        $('#employee_id option:selected').remove();
        $('#regional_id option:selected').remove();
        $("div#select-regional .select2-selection--single").css('border', '1px solid #aaa');
        $("div#select-employee .select2-selection--single").css('border', '1px solid #aaa');
        $('#regional_idFeedback').hide()
        $('#employee_idFeedback').hide()
        $('#select-employee').attr('data-id', '1')
        
    });

// //post data 
    $(document).on('click', '.input-approval', function(e){
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
                        url : APP_URL + "api/add-regional-pic",
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
                                        html : response.responseJSON.data.error
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
        
        $(".modal-title").html('Update Regional PIC')
        $("#input-approval").removeClass('input-approval');
        $("#input-approval").addClass('update-approval');
        $('#select-employee').attr('data-id', '0')

        
        var id = $(this).val();
        

        var token = $('#token').val()

        
        $.ajax({
            url : APP_URL + "api/get-regional-pic/"+ id,
            type : 'GET',
            dataType : 'json',
            beforeSend: function(xhr, settings) { 
                xhr.setRequestHeader('Authorization','Bearer ' + token ); 
            },
            success : function(response){
                $('#employee_id').append(`
                    <option selected value="${response.data.employee.employee_id}">${response.data.employee.employee_name}</option>
                `)
                $('#regional_id').append(`
                    <option selected value="${response.data.regional.regional_id}">${response.data.regional.regional_name}</option>
                `)

                $('#input-approval').val(response.data.regional_pic['id']);
            },
            error:function(response){
                if (!response.success) {

                        console.log(response.responseJSON.data.error);
                    
                }
            }

        });
        

    });

// //update approval
$(document).on('click', '.update-approval', function(e){
    e.preventDefault();
    
    
    var token = $('#token').val()
    var id = $(this).val();

    data = {
        'employee_id' : $('#employee_id').val(),
        'regional_id' : $('#regional_id').val()
    }


    $.ajax({
        type : "POST",
        url : APP_URL + "api/update-regional-pic/"+ id,
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
                timer: 2000,
                willClose: () => {
                    table.draw()
                }
            }).then((result) => {
                if (result.dismiss === Swal.DismissReason.timer) {
                    
                    table.draw()
                    

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
                url: APP_URL + "api/delete-regional-pic/" + id,
                dataType: "json",
                beforeSend: function(xhr, settings) { 
                    xhr.setRequestHeader('Authorization','Bearer ' + token ); 
                },
                success: function(response){
                    
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
                        url: APP_URL + "api/delete-all-approval",
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

    // Select Sub Regional

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var token = $('#token').val()
    var selectRegional =   $('#regional_id').select2({
                            placeholder : "Select Regional",
                            dropdownParent: $("#staticBackdrop"),
                            ajax: { 
                                url: () => {
                                    var employeeId = $('#employee_id').val()
                                    var dataId = $('#select-employee').attr('data-id')
                                    var url
                                    console.log(dataId);
                                    if(dataId == 1){
                                        return url = APP_URL + "api/select-regional"
                                    }else{
                                        return url = APP_URL + "api/select-regional/"+employeeId
                                    }
                                },
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
                            url:  () => { 
                                var regionalId = $("#regional_id").val()
                                var url
                                
                                if (regionalId == null) {
                                    return url = "api/select-employee/0"
                                }else{
                                    return url = "api/select-employee/"+regionalId
                                }
                             },
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
                                    if (item == null) {
                                        return{
                                            text : "please select regional",
                                            id: null
                                        }
                                    }else{
                                        return{
                                            text : item.text,
                                            id: item.id
                                        }
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
