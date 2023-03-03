class Ajax{

    constructor(url, data, type){
        this.url = url
        this.data = data
        this.type = type
    }


    loginAjaxFunction(url){
        return $.ajax({
            type : this.type,
            url : this.url,
            data : this.data,
            dataType : "json",
            beforeSend: function(xhr, settings) { 
                $("#modal-loading").modal('show')
            },
            success : function(response){
                setTimeout(()=>{
                    if (response.data.role.role_id == null) {
                        var role = response.data.role
                        var isUrl = url + response.data.token
                    }else{
                        var role = response.data.role.role_id
                        var isUrl = url
                    }

                    var modal = new ModalAlert
                    $('#modal-loading').modal('hide')
                    if (role != 0) {
                        modal.modalLoginError()
                    }else{
                        modal.modalLoginSuccess(isUrl)
                    }
                },500)
                
                
            },
            error:function(response){
                setTimeout(()=>{
                    $('#modal-loading').modal('hide')
                    if (!response.success) {
                        if (response.status === 403) {
                            var modal = new ModalAlert
                            modal.modalLoginError()
                        }else{
                            $('#alert').show();
                            $('#alert').html(response.responseJSON.data.error);
                        }
                    }
                },500)
            }
            
        })


    }


    ajaxFunction(tableData, modalInputId = null){
        return $.ajax({
            type : this.type,
            url : this.url,
            data : this.data,
            dataType : "json",
            beforeSend: function(xhr, settings) {
                xhr.setRequestHeader('Authorization','Bearer ' + AUTH_TOKEN ); 
                $('#modal-loading').modal('show')
            },
            success : function(response){
                var modal = new ModalAlert
                if (response.success) {
                    if (modalInputId) {
                        $(modalInputId).modal('hide')
                    }
                    setTimeout(()=>{
                        $('#modal-loading').modal('hide')
                        modal.modalAlertSuccess(response.message, tableData)
                    },500)
                }
                
                
            },
            error:function(response){
                if (!response.success) {
                    var modal = new ModalAlert
                    setTimeout(()=>{
                        $('#modal-loading').modal('hide')
                        modal.modalAlertError(response.responseJSON.data.error, this.type)
                    },500)
                }
            }
            
        })
    }


    ajaxForgotPassword(token){
        return $.ajax({
            type : this.type,
            url : this.url,
            data : this.data,
            dataType : "json",
            beforeSend: function(xhr, settings) {
                xhr.setRequestHeader('Authorization','Bearer ' + token ); 
                $('#modal-loading').modal('show')
            },
            success : function(response){
                var modal = new ModalAlert
                if (response.success) {
                    setTimeout(()=>{
                        $('#modal-loading').modal('hide')
                        modal.modalLoginSuccess("")
                    },500)
                }
                
                
            },
            error:function(response){
                if (!response.success) {
                    setTimeout(()=>{
                        $('#modal-loading').modal('hide')
                        $('#alert').show();
                        $('#alert').html(response.responseJSON.data.error);
                    },500)
                }
            }
            
        })
    }

}