//  Notification Success
function showSuccess(message) {
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: message,
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        background: '#f0fdf4',
        color: '#166534'
    });
}

//  Notification Error
function showError(message) {
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'error',
        title: message,
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        background: '#fef2f2',
        color: '#991b1b'
    });
}

//  Nettoyage sessionStorage
sessionStorage.clear();
