$(document).ready(function () {
    var token = $('#token').val()
    tableRegional()
    function tableRegional() { 
        var token = $('#token').val()
        return $('#regionalTable').DataTable({
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

            $('#regionalTable').DataTable().destroy()
            ajaxFunction("api/add-regional", "POST", data, token, tableRegional(), "#staticBackdrop")
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
            $('#regionalTable').DataTable().destroy()
            ajaxFunction("api/update-regional/"+ id, "POST", data, token, tableRegional(), "#staticBackdrop")
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
                $('#regionalTable').DataTable().destroy()
                ajaxFunction("api/delete-regional/" + id, "DELETE", false, token, tableRegional(), "#staticBackdrop")
              
            }
          })

        

    });





});