// ===================== Notifications =====================
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

// ===================== Modal multi-step =====================
document.addEventListener("DOMContentLoaded", () => {
    const steps = document.querySelectorAll(".step");
    const stepItems = document.querySelectorAll(".step-item");
    const progress = document.getElementById("stepProgress");
    const form = document.getElementById("reservationForm");
    let currentStep = 0;

    function showStep(index) {
        steps.forEach((step, i) => step.classList.toggle("d-none", i !== index));
        stepItems.forEach((item, i) => item.classList.toggle("active", i <= index));
        progress.style.width = ((index + 1) / steps.length) * 100 + "%";
    }

    function validateStep(stepIndex) {
        let valid = true;
        const inputs = steps[stepIndex].querySelectorAll("input, select, textarea");

        inputs.forEach(input => {
            if (!input.checkValidity()) {
                input.classList.add("is-invalid");
                valid = false;
            } else {
                input.classList.remove("is-invalid");
            }
        });

        // Validation dates step 1
        if (stepIndex === 0) {
            const today = new Date().toISOString().split("T")[0];
            const dateDepart = document.getElementById("du").value;
            const dateArrivee = document.getElementById("au").value;

            if (dateDepart && dateDepart < today) {
                Swal.fire("Date invalide", "La date de départ ne peut pas être inférieure à aujourd'hui.", "warning");
                valid = false;
            }
            if (dateDepart && dateArrivee && dateArrivee < dateDepart) {
                Swal.fire("Date invalide", "La date d'arrivée doit être postérieure à la date de départ.", "warning");
                valid = false;
            }
        }

        return valid;
    }

    document.querySelectorAll(".next-step").forEach(btn => {
        btn.addEventListener("click", () => {
            if (validateStep(currentStep) && currentStep < steps.length - 1) {
                currentStep++;
                showStep(currentStep);
            }
        });
    });

    document.querySelectorAll(".prev-step").forEach(btn => {
        btn.addEventListener("click", () => {
            if (currentStep > 0) {
                currentStep--;
                showStep(currentStep);
            }
        });
    });

    form.addEventListener("submit", (e) => {
        e.preventDefault();
        if (!validateStep(currentStep)) return;

        const omOui = document.getElementById("omOui").checked;
        const omNon = document.getElementById("omNon").checked;

        if (!omOui && !omNon) {
            Swal.fire("Validation OM", "Veuillez indiquer si l’OM est validé.", "warning");
            return;
        }
        if (omNon) {
            Swal.fire("OM non validé", "Impossible d’envoyer la réservation si l’OM n’est pas validé.", "error");
            return;
        }

        Swal.fire({
            title: "Confirmer la réservation",
            text: "Voulez-vous vraiment envoyer cette réservation ?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Oui, envoyer",
            cancelButtonText: "Annuler"
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: "Envoi en cours...",
                    text: "Veuillez patienter...",
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => Swal.showLoading()
                });

                setTimeout(() => form.submit(), 1000);
            }
        });
    });

    showStep(currentStep);
});

// ===================== Modal suppression =====================
document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const form = this.closest('form');
        const backdrop = document.getElementById('deleteBackdrop');
        const modal = document.getElementById('deleteModal');
        const deleteForm = document.getElementById('deleteForm');

        // Mettre à jour le formulaire pour la réservation ciblée
        deleteForm.action = form.action;

        backdrop.classList.remove('d-none');
        modal.classList.remove('d-none');
    });
});

document.addEventListener('DOMContentLoaded', function() {

    const modal = document.getElementById('deleteModal');
    const backdrop = document.getElementById('deleteBackdrop');
    const confirmBtn = document.getElementById('confirmDelete');
    const cancelBtn = document.getElementById('cancelDelete');
    let currentForm = null;

    // Ouvrir le modal
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function() {
            currentForm = this.closest('form'); 
            modal.classList.remove('d-none');
            backdrop.classList.remove('d-none');
        });
    });

    // Fermer le modal
    function closeModal() {
        modal.classList.add('d-none');
        backdrop.classList.add('d-none');
        currentForm = null;
    }

    cancelBtn.addEventListener('click', closeModal);
    backdrop.addEventListener('click', closeModal);

    // Confirmer la suppression
    confirmBtn.addEventListener('click', function() {
        if(currentForm){
            currentForm.submit(); 
        }
    });

});

// ===================== Préremplir le modal "Modifier" =====================
document.addEventListener("DOMContentLoaded", () => {
    const editButtons = document.querySelectorAll(".editBtn");
    const editForm = document.getElementById("editForm");

    editButtons.forEach(button => {
        button.addEventListener("click", () => {
            // Récupération des attributs data-*
            const id = button.dataset.id;
            const type = button.dataset.type_reservation;
            const depart = button.dataset.date_depart;
            const arrivee = button.dataset.date_arrivee;
            const lieuDepart = button.dataset.lieu_depart;
            const lieuArrive = button.dataset.lieu_arrive;
            const places = button.dataset.nombre_places;
            const passagers = button.dataset.liste_passagers;
            const motif = button.dataset.motif;
            const ordre = button.dataset.ordre_mission;

            // Injection dans le formulaire
            editForm.action = `/reservations/${id}`; // ⚠️ route update
            document.getElementById("edit-id").value = id;
            document.getElementById("edit-type").value = type;
            document.getElementById("edit-depart").value = depart;
            document.getElementById("edit-arrivee").value = arrivee;
            document.getElementById("edit-lieu-depart").value = lieuDepart;
            document.getElementById("edit-lieu-arrive").value = lieuArrive;
            document.getElementById("edit-places").value = places;
            document.getElementById("edit-passagers").value = passagers;
            document.getElementById("edit-motif").value = motif;
            document.getElementById("edit-ordre").value = ordre;
        });
    });
});

