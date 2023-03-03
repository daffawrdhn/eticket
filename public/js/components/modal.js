class ModalAlert{
    modalAlertSuccess(title, tableData){
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: title,
            showConfirmButton: false,
            timer: 2000,
            willClose: () => {
                var table = new Table(tableData)
                $(tableData.tableId).DataTable().destroy()
                table.dataTable(AUTH_TOKEN)
            }
        }).then((result) => {
            if (result.dismiss === Swal.DismissReason.timer) {
                var table = new Table(tableData)
                $(tableData.tableId).DataTable().destroy()
                table.dataTable(AUTH_TOKEN)
            }
        })
    }


    modalAlertError( message, type = null){
        $("#loading").modal('hide');
        if (type == "DELETE") {

            Swal.fire({
                icon : 'warning',
                confirmButtonText: 'Ok',
                title : 'Warning!',
                text : message,
            })
                   
        }else{
            Swal.fire({
                icon : 'warning',
                confirmButtonText: 'Ok',
                title : 'Warning!',
                html : '<ul style="list-style:none;"></ul>',
                didOpen: () => {
                    const ul = Swal.getHtmlContainer().querySelector('ul')
                    $.each(message, function (key, value) { 
                         $(ul).append('<li>'+ value +'</li>');
                    });
                  },
            })
        }
    }


    modalLoginError(){
        Swal.fire({
            icon : 'error',
            title: 'You are not Administrator!!',
            showDenyButton: false,
            showCancelButton: false,
            confirmButtonText: 'Ok',
            willClose: () => {
                window.location.href = APP_URL + "logout";
            }
          }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = APP_URL + "logout";
            } 
        })
    }

    modalLoginSuccess(url){
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Login is Successfully',
            showConfirmButton: false,
            timer: 1000
        }).then((result) => {
            if (result.dismiss === Swal.DismissReason.timer) {
                window.location.href = APP_URL + url;
            }
        })
    }

    loading(action){
        if (action === 'show') {
            $("#modal-loading").modal(action)
        }
    }
}