$(document).ready(function () {

});



    
    // $.ajax({
    //     url : APP_URL + "api/get-user",
    //     type : 'GET',
    //     dataType : 'json',
    //     beforeSend: function(xhr, settings) { 
    //         xhr.setRequestHeader('Authorization','Bearer ' + token ); 
    //     },
    //     success : function(response){

    //             $('#table-employee').html('');
                
    //             no = 1
    //             $(response.data).each(function(key, values){
    //                 $('#table-employee').append(
    //                         `<tr id="${values.employee_id}">
    //                             <td id="employee-list">`+values.employee_id+`</td>
    //                             <td id="employee-list">`+values.employee_ktp+`</td>
    //                             <td id="employee-list" class="employee_name">`+values.employee_name+`</td>
    //                             <td id="employee-list">`+values.employee_email+`</td>
    //                             <td id="employee-list">`+values.role.role_name+`</td>
    //                             <td id="employee-list">`+values.organization.organization_name+`</td>
    //                             <td id="employee-list">`+values.regional.regional_name+`</td>
    //                             <td id="employee-list">`+
    //                                 (values.status == 'Active' ? '<button class="btn btn-sm btn-success" id="status" value="'+ values.status +'" data-id="'+ values.employee_id +'">'+ values.status +'</button>': 
    //                                 '<button class="btn btn-sm btn-danger" id="status" value="'+ values.status +'" data-id="'+ values.employee_id +'">'+ values.status +'</button>')
    //                             +`</td>
    //                             <td>
    //                                 <div class="btn-group">
    //                                     <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
    //                                         <i class="bi bi-gear-fill"></i>
    //                                     </button>
    //                                     <ul class="dropdown-menu">
    //                                     <li><a class="dropdown-item" id="reset-pass" href="#" data-id="`+ values.employee_id +`">reset Pass</a></li>
    //                                     <li><a class="dropdown-item" id="edit-user" href="#" data-id="`+ values.employee_id +`" data-bs-toggle="modal" data-bs-target="#modalAddUser">Update</a></li>
    //                                     <li><a class="dropdown-item" id="delete-user" data-id="`+ values.employee_id +`" href="#">Delete</a></li>
    //                                     </ul>
    //                                 </div>
    //                             </td>
    //                         </tr>`)

                            
    //             })
                 
                

    //     },
    //     error:function(response){
    //         if (!response.success) {
    //                 console.log(response.responseJSON.data.error);
                
    //         }
    //     }

    // })

search()



// status

function search() {
    $("#search").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#table-employee tr").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
      });
}


// get data employee


// get employee by id
$(document).on('click', '#edit-user', function(e){
    e.preventDefault();
    
    $(".modal-title").html('Update User')
    $("#input-user").removeClass('input-user');
    $("#input-user").addClass('update-user');


    

    
    var id = $(this).attr("data-id")

    var token = $('#token').val()
    $.ajax({
        url : APP_URL + "api/get-user/"+ id,
        type : 'GET',
        dataType : 'json',
        beforeSend: function(xhr, settings) { 
            xhr.setRequestHeader('Authorization','Bearer ' + token ); 
        },
        success : function(response){
            console.log(response);

                $('#input-user').val(response.data.employee_id);
                $('#employee_name').val(response.data.employee_name);
                $('#employee_ktp').val(response.data.employee_ktp);
                $('#employee_email').val(response.data.employee_email);
                $('#employee_birth').val(response.data.employee_birth);
                $('#join_date').val(response.data.join_date);
                $('#quit_date').val(response.data.quit_date);

                $('#organization_id').append(`
                    <option selected value="${response.data.organization['organization_id']}">${response.data.organization['organization_name']}</option>
                `)
                $('#regional_id').append(`
                    <option selected value="${response.data.regional['regional_id']}">${response.data.regional['regional_name']}</option>
                `)

                $('#role_id').append(`
                    <option selected value="${response.data.role['role_id']}">${response.data.role['role_name']}</option>
                `)

                $('#supervisor_id').append(`
                    <option selected value="${response.data.supervisor_id}">${response.data.supervisor['employee_name']}</option>
                `)
        },
        error:function(response){
            if (!response.success) {

                    console.log(response.responseJSON.data.error);
                
            }
        }

    });
    

});

// getDataOrganization

function getDataOrganization(){
    var token = $('#token').val()
    $.ajax({
        url : APP_URL + "api/get-organization",
        type : 'GET',
        dataType : 'json',
        beforeSend: function(xhr, settings) { 
            xhr.setRequestHeader('Authorization','Bearer ' + token ); 
        },
        success : function(response){

                
                no = 1;
                $(response.data).each(function(key, values){

                    $('#organization_id').append(`
                        <option value="`+ values.organization_id +`">`+ values.organization_name +`</option>
                    `);
                })
            

        }

    });
}

// getData Role
function getDataRole(){
    var token = $('#token').val()
    $.ajax({
        url : APP_URL + "api/get-role",
        type : 'GET',
        dataType : 'json',
        beforeSend: function(xhr, settings) { 
            xhr.setRequestHeader('Authorization','Bearer ' + token ); 
        },
        success : function(response){
            $(response.data).each(function(key, values){

                $('#role_id').append(`
                    <option value="`+ values.role_id +`">`+ values.role_name +`</option>
                `);
                
            })
        },error:function(response){
            if (!response.success) {
                    // $('#alert').show();
                    // $('#alert').html(response.responseJSON.data.error);


                    console.log(response.responseJSON.data.error);
                
            }
        }

    });
}


//getData Regional
function getDataRegional(){
    var token = $('#token').val()
    $.ajax({
        url : APP_URL + "api/get-regional",
        type : 'GET',
        dataType : 'json',
        beforeSend: function(xhr, settings) { 
            xhr.setRequestHeader('Authorization','Bearer ' + token ); 
        },
        success : function(response){

                
                no = 1;
                $(response.data).each(function(key, values){

                    $('#regional_id').append(`
                    <option value="`+ values.regional_id +`">`+ values.regional_name +`</option>
                    `);
                })
            

        }

    });
}