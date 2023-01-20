$(document).ready(function () {

    
    

    //getdata
    var token = $('#token').val()
    var table = $('#deptheadTable').DataTable({
        responsive: true,
        autoWidth : false,
        processing: true,
        serverSide: true,
        ajax: { 
            url: APP_URL + "api/get-depthead",
            type: "GET",
            dataType: 'json',
            beforeSend: function(xhr, settings) { 
                xhr.setRequestHeader('Authorization','Bearer ' + token ); 
            },
        },
        columns: [
            {data: 'no', name: 'no'},
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
        $(".modal-title").html('Add New Dept Head')
        $("#input-approval").removeClass('update-depthead');
        $("#input-approval").addClass('input-depthead');
        $('#employee_id option:selected').remove();
        $("div#select-employee .select2-selection--single").css('border', '1px solid #aaa');
        $('#employee_idFeedback').hide()
        
    });

// //post data 
    $(document).on('click', '.input-depthead', function(e){
        e.preventDefault();
        
        var token = $('#token').val()

        data = {
            'employee_id' : $('#employee_id').val(),
        }
        if (data.employee_id == null) {
            $('#employee_idFeedback').html('please fill out this field')
            $('#employee_idFeedback').show()
            $("div#select-employee .select2-selection--single").css('border', '1px solid red');
            
        }else{
            $(".select2-selection--single").css('border', '1px solid #aaa');
            $('#employee_idFeedback').hide()

            $.ajax({
                type : "POST",
                url : APP_URL + "api/add-depthead",
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
    });

    $(document).on('click', '#edit-approval', function(e){
        e.preventDefault();
        
        $(".modal-title").html('Update Dept Head')
        $("#input-approval").removeClass('input-approval');
        $("#input-approval").addClass('update-approval');
        $("div#select-employee .select2-selection--single").css('border', '1px solid #aaa');
        $('#employee_idFeedback').hide()
        
        var id = $(this).val();
        

        var token = $('#token').val()

        
        $.ajax({
            url : APP_URL + "api/get-depthead/"+ id,
            type : 'GET',
            dataType : 'json',
            beforeSend: function(xhr, settings) { 
                xhr.setRequestHeader('Authorization','Bearer ' + token ); 
            },
            success : function(response){
                $('#employee_id').append(`
                    <option selected value="${response.data.employee.employee_id}">${response.data.employee.employee_name}</option>
                `)

                $('#input-approval').val(response.data.depthead['id']);
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
        url : APP_URL + "api/update-depthead/"+ id,
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
                url: APP_URL + "api/delete-depthead/" + id,
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
                        url: APP_URL + "api/delete-all-depthead",
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

    //select employee
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var token = $('#token').val()
    var selectUser =   $('#employee_id').select2({
                        placeholder : "Select Employee",
                        dropdownParent: $("#staticBackdrop"),
                        ajax: { 
                            url:  APP_URL + "api/select-user",
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
