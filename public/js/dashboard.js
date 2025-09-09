/* ============================================================
   Notifications SweetAlert2
   ============================================================ */
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

// ✅ Messages flash Laravel
if (sessionStorage.getItem('success')) {
    showSuccess(sessionStorage.getItem('success'));
    sessionStorage.removeItem('success');
}
if (sessionStorage.getItem('error')) {
    showError(sessionStorage.getItem('error'));
    sessionStorage.removeItem('error');
}


/* ============================================================
   Graphiques Chart.js
   ============================================================ */

//  Barres - Réservations par jour
const ctx = document.getElementById('barChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: reservationLabels,
        datasets: [{
            label: 'Réservations par jour',
            data: reservationValues,
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: { 
            y: { beginAtZero: true, ticks: { precision: 0 } } 
        }
    }
});

//  Donut - Statut des réservations
const statutData = {
    labels: ['Validées', 'Rejetées', 'En cours'],
    values: [validees, rejetee, encours]
};

const ctx2 = document.getElementById('progressCircle').getContext('2d');
new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: statutData.labels,
        datasets: [{
            data: statutData.values,
            backgroundColor: ['#2e7d32','#c62828','gray'],
            borderWidth: 1
        }]
    },
    options: {
        cutout: '80%',
        responsive: true,
        plugins: { 
            legend: { position: 'bottom', labels: { color: '#333' } },
            tooltip: { enabled: true } 
        }
    }
});

//  Pourcentage validées
const total = statutData.values.reduce((acc, val) => acc + val, 0);
const pourcentage = total ? Math.round((statutData.values[0] / total) * 100) : 0;
document.getElementById('progress-value').textContent = `${pourcentage}% Validées`;


/* ============================================================
   Modal utilisateur (Bootstrap)
   ============================================================ */
document.addEventListener('DOMContentLoaded', function() {
    const modal = new bootstrap.Modal(document.getElementById('userModal'));
    const nameSpan = document.getElementById('modalUserName');
    const emailSpan = document.getElementById('modalUserEmail');

    document.querySelectorAll('.view-user-btn').forEach(button => {
        button.addEventListener('click', function() {
            nameSpan.textContent = this.dataset.name;
            emailSpan.textContent = this.dataset.email;
            modal.show();
        });
    });
});


/* ============================================================
   Export boutons (PDF, Excel, etc.)
   ============================================================ */
document.addEventListener('DOMContentLoaded', () => {
    const exportButtons = document.querySelectorAll('.btn-export');

    exportButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            exportButtons.forEach(b => b.classList.remove('active', 'loading'));
            btn.classList.add('active', 'loading');
            btn.textContent = 'Téléchargement...';

            const format = btn.dataset.format;
            const link = document.createElement('a');
            link.href = `/reservations/export?format=${format}`;
            link.click();

            setTimeout(() => {
                btn.classList.remove('loading');
                btn.textContent = format.toUpperCase();
            }, 3000);
        });
    });
});


/* ============================================================
   Stepper + Validation multi-étapes
   ============================================================ */
document.addEventListener("DOMContentLoaded", () => {
    const steps = document.querySelectorAll(".step");
    const progress = document.getElementById("stepProgress");
    const stepItems = document.querySelectorAll(".step-item");
    const form = document.getElementById("reservationForm");
    let currentStep = 0;

    function showStep(index) {
        steps.forEach((step, i) => {
            step.classList.toggle("d-none", i !== index);
            stepItems[i].classList.toggle("active", i <= index);
        });
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

        // Dates
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
            if (validateStep(currentStep)) {
                if (currentStep < steps.length - 1) {
                    currentStep++;
                    showStep(currentStep);
                }
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

                setTimeout(() => {
                    form.submit();
                }, 1000);
            }
        });
    });

    showStep(currentStep);
});
