<html lang="fr">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Dashboard Admin</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css"/>
<link rel="stylesheet" href=" {{asset('css/dashboard.css')}}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <h2>MENU</h2>
  <ul>
    <li class="menu-item active"><i class="ri-dashboard-line"></i>Dashboard</li>
    <a href="{{ route('dashboardVehicule') }}" style="text-decoration: none; color:aliceblue;"> <li class="menu-item"><i class="ri-car-line"></i> Management des vehicules</li></a>
    <li class="menu-item"><i class="ri-tools-fill"></i> Maintenance</li>
    <li class="menu-item"><i class="ri-add-box-line"></i> Renouvellement de pieces</li>
    <li class="menu-item"><i class="ri-water-flash-fill"></i> Carburant </li>
  </ul>

<div class="admin-profile">
  <div class="avatar"> 
{{-- @if($user->profile_image)
  <img src="{{ $user->profile_image }}" alt="Photo de profil" class="img-thumbnail" width="150" height="150">
@else
  <img src="https://res.cloudinary.com/demo/image/upload/w_150,h_150,c_thumb/default_avatar.png" alt="Default" class="img-thumbnail" width="150" height="150">
@endif --}}

{{-- @if(auth()->user()->profile_image)
    <img src="{{ auth()->user()->profile_image }}" alt="Photo de profil" width="80" height="80" style="border-radius:50%;">
@else
    <img src="{{ asset('images/default-avatar.png') }}" alt="Avatar par défaut" width="80" height="80" style="border-radius:50%;">
@endif --}}

</div>
  <div class="admin-info">

    <strong>{{ Auth::user()->name }} ({{ Auth::user()->role }})</strong>
    <a href="{{ route('logout') }}" class="logout-link">Se déconnecter</a>
  </div>
</div>

</div>

<div class="main">
  <div class="header">
    <h1>Eneo Fleet Service</h1>
    <div class="buttons-group">
      <a href="../reservation"><button class="btn-add">+ Ajouter une reservation</button></a>
      <button onclick="exportRapport()">Generer le rapport</button></a>
    </div>
  </div>

  <div class="cards">
    <a href="{{ route('dashboard') }}" style="text-decoration: none;">
    <div class="card" style="background-color:#d0f0c0;">
      <h3>Total réservation</h3>
      <div class="stat">{{ $total }}</div>
    </div>
    </a>

    <a href="{{ route('dashboard.filtre', 'validee') }}" style="text-decoration: none;">
    <div class="card" style="background-color:#d0f0c0;">
      <h3>Réservation Validée</h3>
      <div class="stat">{{ $validees }}</div>
    </div>
    </a>

    <a href="{{ route('dashboard.filtre', 'rejetee') }}" style="text-decoration: none;">
    <div class="card" style="background-color:#d0f0c0;">
      <h3>Réservation Rejetée</h3>
      <div class="stat">{{ $rejetee }}</div>
    </div>
    </a>

    <a href="{{ route('dashboard.filtre', 'en cours') }}" style="text-decoration: none;">
    <div class="card" style="background-color:#d0f0c0;">
      <h3>Réservation en cours</h3>
      <div class="stat">{{ $encours }}</div>
    </div>
    </a>
</div>


  <!-- Graphique Analytics -->
   <div class="cards">
  <div class="chart-container">
    <h3>Reservation journalieres</h3>
    <canvas id="barChart" width="400" height="200"></canvas>
  </div>

  <!-- Reunion -->
  <div class="reminder">
    <h3>Reunion</h3>
    <p>Reunion le 08 aout 2025<br>08h00 - 09h00 </p>
  </div>

  <!-- Progress Circle -->
  <div class="progress-section">
    <h3>Statut des reservations</h3>
    <canvas id="progressCircle"></canvas>
    <div class="progress-percent" id="progress-value"></div>
  </div>

  </div>

  <div class="container">
    <h1>Réservations</h1>

    <table class="custom-table">
        <thead>
            <tr>
                <th>Type</th>
                <th>Départ</th>
                <th>Arrivée</th>
                <th>Lieu Départ</th>
                <th>Lieu Arrivée</th>
                <th>Places</th>
                <th>Passagers</th>
                <th>Motif</th>
                <th>Ordre Mission</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reservations as $reservation)

<tr id="reservation-{{ $reservation->id }}">
    <td>{{ $reservation->type_reservation }}</td>
    <td>{{ $reservation->date_depart }}</td>
    <td>{{ $reservation->date_arrivee }}</td>
    <td>{{ $reservation->lieu_depart }}</td>
    <td>{{ $reservation->lieu_arrive }}</td>
    <td>{{ $reservation->nombre_places }}</td>
    <td>{{ $reservation->liste_passagers }}</td>
    <td>{{ $reservation->motif }}</td>
    <td>{{ $reservation->ordre_mission }}</td>

<td>
    @php
        $statut = $reservation->statut ?? 'en cours';
    @endphp

    <span class="badge 
        {{ $statut === 'validee' ? 'bg-success' : 
           ($statut === 'rejetee' ? 'bg-danger' : 'bg-secondary') }}">
        {{ ucfirst($statut) }}
    </span>
</td>
 
    <td>
<div style="display: flex; align-items: center; gap: 8px;">
      <button class="btn btn-info btn-sm view-user-btn"
            style="background-color: gray; color: white; padding: 0.25rem 0.5rem; font-size: 0.5rem; border: 1px solid black;"
            data-name="{{ $reservation->user->name ?? 'Utilisateur supprimé' }}"
            data-email="{{ $reservation->user->email ?? '-' }}">
        <i class="ri-search-eye-line"></i>
    </button>

       <form action="{{ route('reservations.updateStatus', $reservation->id) }}" method="POST">
    @csrf
    @method('PUT')
    <select name="statut" onchange="this.form.submit()" class="form-select">
        <option value="en cours" {{ $reservation->statut === 'en cours' ? 'selected' : '' }}>En cours</option>
        <option value="validee" {{ $reservation->statut === 'validee' ? 'selected' : '' }}>Validée</option>
        <option value="rejetee" {{ $reservation->statut === 'rejetee' ? 'selected' : '' }}>Rejetée</option>
    </select>
  </div>
    </td>
</form>


            </tr>
            @endforeach
        </tbody>
    </table>
    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-3">
        {{ $reservations->links('pagination::bootstrap-5') }}
    </div>
</div>

<!-- Modal utilisateur -->
<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Informations de l utilisateur</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <p><strong>Nom :</strong> <span id="modalUserName"></span></p>
        <p><strong>Email :</strong> <span id="modalUserEmail"></span></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>

    </form>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
    <script>
      showSuccess(@json(session('success')));
    </script>
  @endif

  @if(session('error'))
    <script>
      showError(@json(session('error')));
    </script>
  @endif

<script>
  // Graphique - Bar Chart
  
  const ctx = document.getElementById('barChart').getContext('2d');
    const barChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($reservationLabels) !!},
            datasets: [{
                label: 'Réservations par jour',
                data: {!! json_encode($reservationValues) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

  // Graphique - Progress Circle
  
// Graphique - Progress Circle 
const statutData = {
    labels: ['Validées', 'Rejetées', 'En cours'],
    values: [{{ $validees }}, {{ $rejetee }}, {{ $encours }}]
};

const ctx2 = document.getElementById('progressCircle').getContext('2d');
const progressChart = new Chart(ctx2, {
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
                labels: {
                    color: '#333'
                }
            },
            tooltip: {
                enabled: true
            }
        }
    }
});

// Affiche la part validée en pourcentage
const total = statutData.values.reduce((acc, val) => acc + val, 0);
const validees = statutData.values[0]; // première valeur = validées
const pourcentage = total ? Math.round((validees / total) * 100) : 0;
document.getElementById('progress-value').textContent = `${pourcentage}% Validées`;

document.addEventListener('DOMContentLoaded', function() {
    const modal = new bootstrap.Modal(document.getElementById('userModal'));
    const nameSpan = document.getElementById('modalUserName');
    const emailSpan = document.getElementById('modalUserEmail');

    document.querySelectorAll('.view-user-btn').forEach(button => {
        button.addEventListener('click', function() {
            const name = this.dataset.name;
            const email = this.dataset.email;

            nameSpan.textContent = name;
            emailSpan.textContent = email;

            modal.show();
        });
    });
});

sessionStorage.setItem("user_id", "{{ session('user_id') }}");
sessionStorage.setItem("user_name", "{{ session('user_name') }}");

function exportRapport() {
    let format = prompt("Sous quel format voulez-vous générer le rapport ? (csv/pdf)", "csv");
    if(format === "csv") {
        window.location.href = "{{ route('reservations.exportCsv') }}";
    } else if(format === "pdf") {
        window.location.href = "{{ route('reservations.exportPdf') }}";
    } else {
        alert("Format non supporté !");
    }
}

</script>
</body>
</html>