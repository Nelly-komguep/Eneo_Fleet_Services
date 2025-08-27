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

function openEditModal(reservation) {
    const form = document.getElementById('editForm');
    form.action = `/reservations/${reservation.id}`;

    document.getElementById('edit-id').value = reservation.id;
    document.getElementById('edit-type').value = reservation.type_reservation;
    document.getElementById('edit-depart').value = reservation.date_depart;
    document.getElementById('edit-arrivee').value = reservation.date_arrivee;
    document.getElementById('edit-lieu-depart').value = reservation.lieu_depart;
    document.getElementById('edit-lieu-arrive').value = reservation.lieu_arrive;
    document.getElementById('edit-places').value = reservation.nombre_places;
    document.getElementById('edit-passagers').value = reservation.liste_passagers;
    document.getElementById('edit-motif').value = reservation.motif;
    document.getElementById('edit-ordre').value = reservation.ordre_mission;

    // Ouvre le modal (obligatoire si ouverture JS uniquement)
    const modal = new bootstrap.Modal(document.getElementById('editModal'));
    modal.show();
}

    document.querySelectorAll('.editBtn').forEach(button => {
        button.addEventListener('click', function () {
            const modal = document.getElementById('editModal');

            modal.querySelector('form').action = '/reservations/' + this.dataset.id;

            modal.querySelector('input[name="type_reservation"]').value = this.dataset.type_reservation;
            modal.querySelector('input[name="date_depart"]').value = this.dataset.date_depart;
            modal.querySelector('input[name="date_arrivee"]').value = this.dataset.date_arrivee;
            modal.querySelector('input[name="lieu_depart"]').value = this.dataset.lieu_depart;
            modal.querySelector('input[name="lieu_arrive"]').value = this.dataset.lieu_arrive;
            modal.querySelector('input[name="nombre_places"]').value = this.dataset.nombre_places;
            modal.querySelector('input[name="liste_passagers"]').value = this.dataset.liste_passagers;
            modal.querySelector('input[name="motif"]').value = this.dataset.motif;
            modal.querySelector('input[name="ordre_mission"]').value = this.dataset.ordre_mission;
        });
    });

sessionStorage.setItem("user_id", "{{ session('user_id') }}");
sessionStorage.setItem("user_name", "{{ session('user_name') }}");