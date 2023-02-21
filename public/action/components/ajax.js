function ajaxFunction(url, type, data, token, table, modalName){
    return $.ajax({
        type : type,
        url : APP_URL + url,
        data : data,
        dataType : "json",
        beforeSend: function(xhr, settings) { 
            xhr.setRequestHeader('Authorization','Bearer ' + token );
            
            $("#loading").modal('show')
            
        },
        success : function(response){
            $(modalName).modal('hide');

            setTimeout(()=>{
                $("#loading").modal('hide')
                modalSuccess(response.message, table)
            },500)
            
        },
        error:function(response){
            if (!response.success) {
                
                setTimeout(() => {
                    $("#loading").modal('hide');
                    if (type == "DELETE") {
                        setTimeout(() =>{
                            
                            $("#loading").modal('hide') 
                            modalError(response.responseJSON.data.error)
                            
                        },500)
                               
                    }else{

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
                    }

                },500)
                
                
            }
        }
    })
}