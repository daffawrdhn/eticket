
function modalSuccess(title, table) {
    Swal.fire({
        position: 'center',
        icon: 'success',
        title: title,
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
}

function modalError(text) {
    Swal.fire({
        icon : 'warning',
        confirmButtonText: 'Ok',
        title : 'Warning!',
        text : text,
    })
}
