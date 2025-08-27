// ----------------- Notifications -----------------
if (typeof successMessage !== 'undefined' && successMessage) {
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: successMessage,
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        background: '#f0fdf4',
        color: '#166534'
    });
}

if (typeof errorMessage !== 'undefined' && errorMessage) {
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'error',
        title: errorMessage,
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        background: '#fef2f2',
        color: '#991b1b'
    });
}

// ----------------- Bar Chart -----------------
if (typeof reservationLabels !== 'undefined' && typeof reservationValues !== 'undefined') {
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
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            }
        }
    });
}

// ----------------- Doughnut Chart -----------------
if (typeof statutData !== 'undefined') {
    const ctx2 = document.getElementById('progressCircle').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: statutData.labels,
            datasets: [{
                data: statutData.values,
                backgroundColor: ['#2e7d32', '#c62828', 'gray'],
                borderWidth: 1
            }]
        },
        options: {
            cutout: '80%',
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: '#333' }
                }
            }
        }
    });

    // % Validées
    const total = statutData.values.reduce((acc, val) => acc + val, 0);
    const validees = statutData.values[0];
    const pourcentage = total ? Math.round((validees / total) * 100) : 0;
    document.getElementById('progress-value').textContent = `${pourcentage}% Validées`;
}

// ----------------- Réunions (LocalStorage) -----------------
document.addEventListener('DOMContentLoaded', function () {
    const STORAGE_KEY = 'reunions';
    const form = document.getElementById('reunion-form');
    const list = document.getElementById('reunion-list');
    let reunions = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');

    renderList();

    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const titre = document.getElementById('titre').value.trim();
            const date = document.getElementById('date').value;
            const heureDebut = document.getElementById('heure_debut').value;
            const heureFin = document.getElementById('heure_fin').value;
            if (!titre || !date || !heureDebut || !heureFin) return;

            reunions.push({ id: Date.now(), titre, date, heureDebut, heureFin });
            localStorage.setItem(STORAGE_KEY, JSON.stringify(reunions));
            form.reset();
            bootstrap.Modal.getInstance(document.getElementById('reunionModal')).hide();
            renderList();
        });
    }

    function renderList() {
        if (!list) return;
        if (reunions.length === 0) {
            list.innerHTML = '<p>Aucune réunion enregistrée.</p>';
            return;
        }
        list.innerHTML = '';
        reunions.forEach(r => {
            const div = document.createElement('div');
            div.className = 'mt-2 p-2 border rounded d-flex justify-content-between align-items-start gap-3';
            div.innerHTML = `
                <div>
                  <strong>${r.titre}</strong><br>
                  ${new Date(r.date).toLocaleDateString('fr-FR')}<br>
                  ${r.heureDebut} - ${r.heureFin}
                </div>
                <button class="btn btn-sm btn-outline-danger">Supprimer</button>
            `;
            div.querySelector('button').addEventListener('click', () => {
                reunions = reunions.filter(x => x.id !== r.id);
                localStorage.setItem(STORAGE_KEY, JSON.stringify(reunions));
                renderList();
            });
            list.appendChild(div);
        });
    }
});

// ----------------- Modal User -----------------
document.addEventListener('DOMContentLoaded', function () {
    const modal = new bootstrap.Modal(document.getElementById('userModal'));
    document.querySelectorAll('.view-user-btn').forEach(button => {
        button.addEventListener('click', function () {
            document.getElementById('modalUserName').textContent = this.dataset.name;
            document.getElementById('modalUserEmail').textContent = this.dataset.email;
            modal.show();
        });
    });
});

// ----------------- Export Rapport -----------------
function exportRapport() {
    let format = prompt("Sous quel format voulez-vous générer le rapport ? (csv/pdf)", "csv");
    if (format === "csv") {
        window.location.href = routes.exportCsv;
    } else if (format === "pdf") {
        window.location.href = routes.exportPdf;
    } else {
        alert("Format non supporté !");
    }
}
