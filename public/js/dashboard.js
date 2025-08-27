// Affiche messages SweetAlert
function showSuccess(message) {
    Swal.fire('Succès', message, 'success');
}

function showError(message) {
    Swal.fire('Erreur', message, 'error');
}

// Messages flash
if (sessionStorage.getItem('success')) {
    showSuccess(sessionStorage.getItem('success'));
    sessionStorage.removeItem('success');
}

if (sessionStorage.getItem('error')) {
    showError(sessionStorage.getItem('error'));
    sessionStorage.removeItem('error');
}

// Graphique - Bar Chart
const ctx = document.getElementById('barChart').getContext('2d');
const barChart = new Chart(ctx, {
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
        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
    }
});

// Graphique - Progress Circle
const statutData = {
    labels: ['Validées', 'Rejetées', 'En cours'],
    values: [validees, rejetee, encours]
};

const ctx2 = document.getElementById('progressCircle').getContext('2d');
const progressChart = new Chart(ctx2, {
    type: 'doughnut',
    data: { labels: statutData.labels, datasets: [{ data: statutData.values, backgroundColor: ['#2e7d32','#c62828','gray'], borderWidth: 1 }] },
    options: { cutout: '80%', responsive: true, plugins: { legend: { position: 'bottom', labels: { color: '#333' } }, tooltip: { enabled: true } } }
});

// Affiche pourcentage validées
const total = statutData.values.reduce((acc,val)=>acc+val,0);
const pourcentage = total ? Math.round((statutData.values[0]/total)*100) : 0;
document.getElementById('progress-value').textContent = `${pourcentage}% Validées`;

// Modal utilisateur
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

// Export rapport
function exportRapport() {
    let format = prompt("Sous quel format voulez-vous générer le rapport ? (csv/pdf)", "csv");
    if(format === "csv") {
        window.location.href = "/reservations/exportCsv";
    } else if(format === "pdf") {
        window.location.href = "/reservations/exportPdf";
    } else {
        alert("Format non supporté !");
    }
}
