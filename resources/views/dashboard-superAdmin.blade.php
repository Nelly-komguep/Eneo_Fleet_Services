<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Super-Admin</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css"/>
  <link rel="stylesheet" href="{{ secure_asset('css/dashboard.css') }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- ====================== SIDEBAR ====================== -->
<div class="sidebar">
  <h2>MENU</h2>
  <ul>
    <li class="menu-item active"><i class="ri-dashboard-line"></i> Dashboard</li>
    <a href="{{ route('dashboardVehicule') }}" style="text-decoration: none; color:aliceblue;">
      <li class="menu-item"><i class="ri-car-line"></i> Management des véhicules</li>
    </a>
    <li class="menu-item disabled"><i class="ri-tools-fill"></i> Maintenance</li>
    <li class="menu-item disabled"><i class="ri-add-box-line"></i> Renouvellement de pièces</li>
    <li class="menu-item disabled"><i class="ri-water-flash-fill"></i> Carburant </li>
  </ul>

  <!-- Profil admin -->
  <div class="admin-profile">
    <div class="avatar">
      {{-- Avatar utilisateur --}}
      {{-- 
        @if(auth()->user()->profile_image)
          <img src="{{ auth()->user()->profile_image }}" alt="Photo de profil" width="80" height="80" style="border-radius:50%;">
        @else
          <img src="{{ asset('images/default-avatar.png') }}" alt="Avatar par défaut" width="80" height="80" style="border-radius:50%;">
        @endif
      --}}
    </div>
    <div class="admin-info">
      <strong>{{ Auth::user()->name }} ({{ Auth::user()->role }})</strong>
      <a href="{{ route('logout') }}" class="logout-link">Se déconnecter</a>
    </div>
  </div>
</div>

<!-- ====================== CONTENU PRINCIPAL ====================== -->
<div class="main">

  <!-- HEADER -->
  <div class="header">
    <h1>Eneo Fleet Service</h1>
    <div class="buttons-group">
      <!-- Bouton ouverture modal réservation -->
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reservationModal">
       <i class="ri-add-large-fill"></i> Faire une réservation
      </button>

      <!-- Bouton génération rapport -->
     <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exportModal">
    <i class="ri-upload-cloud-line"></i> Générer le rapport
</button>

    </div>
  </div>

  <!-- ====================== MODAL RESERVATION MULTI-STEP ====================== -->
<div class="modal fade" id="reservationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content modal-reservation">

      <!-- Header -->
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Nouvelle réservation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>

      <!-- Timeline textuelle -->
      <div class="stepper-container">
        <div class="stepper">
          <div class="step-item active" data-step="1">
            <div class="circle">1</div>
            <span>Informations</span>
          </div>
          <div class="line"></div>
          <div class="step-item" data-step="2">
            <div class="circle">2</div>
            <span>Détails</span>
          </div>
        </div>
      </div>

      <!-- Progress bar -->
      <div class="progress mt-2" style="height: 5px;">
        <div id="stepProgress" class="progress-bar bg-primary" style="width: 50%;"></div>
      </div>

      <!-- Formulaire multi-step  -->
      <div class="modal-body">
        <form id="reservationForm" method="POST" action="{{ route('reserve.store') }}">
          @csrf

          <!-- ================= STEP 1 ================= -->
          <div class="step step-1">
            <div class="mb-3">
              <label for="type" class="form-label">Type de réservation</label>
              <select class="form-select" id="type" name="type_reservation" required>
                <option value="vehicule">Véhicule</option>
                <option value="vehicule_chauffeur">Véhicule + Chauffeur</option>
                <option value="chauffeur">Chauffeur</option>
              </select>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="du" class="form-label">Du *</label>
                <input type="date" class="form-control" id="du" name="date_depart" required>
              </div>
              <div class="col-md-6 mb-3">
                <label for="au" class="form-label">Au *</label>
                <input type="date" class="form-control" id="au" name="date_arrivee" required>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="de" class="form-label">De *</label>
                <select class="form-select" id="de" name="lieu_depart" required>
                  <option value="Douala">Douala</option>
                  <option value="Yaounde">Yaoundé</option>
                </select>
              </div>
              <div class="col-md-6 mb-3">
                <label for="a" class="form-label">A *</label>
                <select class="form-select" id="a" name="lieu_arrive" required>
                  <option value="Yaounde">Yaoundé</option>
                  <option value="Limbe">Limbe</option>
                  <option value="Kribi">Kribi</option>
                </select>
              </div>
            </div>

            <div class="d-flex justify-content-end">
              <button type="button" class="btn btn-primary next-step">Suivant</button>
            </div>
          </div>

          <!-- ================= STEP 2 ================= -->
          <div class="step step-2 d-none">
            <div class="mb-3">
              <label for="places" class="form-label">Nombre de places</label>
              <input type="number" class="form-control" id="places" name="nombre_places" min="1">
            </div>

            <div class="mb-3">
              <label for="passagers" class="form-label">Liste des passagers</label>
              <input type="text" class="form-control" id="passagers" name="liste_passagers" placeholder="Saisir la liste des passagers">
            </div>

            <div class="mb-3">
              <label for="motif" class="form-label">Motif</label>
              <input type="text" class="form-control" id="motif" name="motif" placeholder="Saisir le motif de la réservation">
            </div>

            <div class="mb-3">
              <label class="form-label">OM validé ? *</label><br>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="om" id="omOui" value="Oui" required>
                <label class="form-check-label" for="omOui">Oui</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="om" id="omNon" value="Non">
                <label class="form-check-label" for="omNon">Non</label>
              </div>
            </div>

            <div class="mb-3">
              <label for="ordre" class="form-label">Ordre de Mission</label>
              <textarea class="form-control" id="ordre" name="ordre_mission" rows="3"></textarea>
            </div>

            <div class="d-flex justify-content-between">
              <button type="button" class="btn btn-secondary prev-step">Précédent</button>
              <button type="submit" class="btn btn-success">Envoyer</button>
            </div>
          </div>

        </form>
      </div>

    </div>
  </div>
</div>

  <!-- ====================== CARDS STATISTIQUES ====================== -->
  <div class="cards1">
    <div class="card" style="background-color:#d0f0c0;">
      <h3>Total réservation</h3>
      <div class="stat">{{ $total }}</div>
    </div>

    <div class="card" style="background-color:#d0f0c0;">
      <h3>Réservation Validée</h3>
      <div class="stat">{{ $validees }}</div>
    </div>

    <div class="card" style="background-color:#d0f0c0;">
      <h3>Réservation Rejetée</h3>
      <div class="stat">{{ $rejetee }}</div>
    </div>

    <div class="card" style="background-color:#d0f0c0;">
      <h3>Réservation en cours</h3>
      <div class="stat">{{ $encours }}</div>
    </div>
  </div>

  <!-- ====================== ANALYTICS & NOTIFICATIONS ====================== -->
  <div class="cards">

    <!-- Graphique bar -->
    <div class="chart-container">
      <h3>Réservations journalières</h3>
      <canvas id="barChart" width="400" height="200"></canvas>
    </div>

    <!-- Notifications -->
    <div class="notification-container" style="position:relative;">
      <h3>Nouvelles...</h3>
      <i id="notifIcon" class="ri-message-3-line" style="font-size:24px; cursor:pointer; position:relative;">
        <span id="notifCount" style="position:absolute; top:-8px; right:-8px; background:red; color:white; border-radius:50%; padding:2px 6px; font-size:12px; display:none;">0</span>
      </i>
      <div id="notifDropdown" style="display:none; position:absolute; right:0; top:40px; background:white; border:1px solid #ddd; width:300px; max-height:300px; overflow-y:auto; box-shadow:0px 2px 8px rgba(0,0,0,0.2); z-index:1000;"></div>
    </div>

    <!-- Graphique doughnut -->
    <div class="progress-section">
      <h3>Statut des réservations</h3>
      <canvas id="progressCircle"></canvas>
      <div class="progress-percent" id="progress-value"></div>
    </div>

  </div>

  <!-- ====================== TABLEAU RESERVATIONS ====================== -->
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

          <!-- Statut -->
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

          <!-- Action -->
          <td>
            <div style="display: flex; align-items: center; gap: 8px;">

              <!-- Bouton voir utilisateur -->
              <button class="btn btn-info btn-sm view-user-btn"
                style="background-color: gray; color: white; padding: 0.25rem 0.5rem; font-size: 0.5rem; border: 1px solid black;"
                data-name="{{ $reservation->user->name ?? 'Utilisateur supprimé' }}"
                data-email="{{ $reservation->user->email ?? '-' }}">
                <i class="ri-search-eye-line"></i>
              </button>

              <!-- Select changement de statut -->
              <form action="{{ route('reservations.updateStatus', $reservation->id) }}" method="POST">
                @csrf
                @method('PUT')
                <select name="statut" onchange="this.form.submit()" class="form-select">
                  <option value="en cours" {{ $reservation->statut === 'en cours' ? 'selected' : '' }}>En cours</option>
                  <option value="validee" {{ $reservation->statut === 'validee' ? 'selected' : '' }}>Validée</option>
                  <option value="rejetee" {{ $reservation->statut === 'rejetee' ? 'selected' : '' }}>Rejetée</option>
                </select>
              </form>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <!-- Pagination -->
    <div class="pagination-container">
      {{ $reservations->links('pagination::bootstrap-5') }}
      <div class="goto-page">
        <span>Go to</span>
        <input type="number" min="1" max="{{ $reservations->lastPage() }}" placeholder="">
        <span>Page</span>
    </div>
  </div>

  <!-- ====================== MODAL UTILISATEUR ====================== -->
  <div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Informations de l'utilisateur</h5>
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
<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content glass-modal">
      <div class="modal-header">
        <h5 class="modal-title" id="exportModalLabel">Télécharger le rapport</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <p>Sous quel format voulez-vous télécharger votre rapport ?</p>
        <div class="buttons d-flex justify-content-center gap-2">
          <button class="btn btn-export active" data-format="pdf">PDF</button>
          <button class="btn btn-export" data-format="csv">CSV</button>
        </div>
      </div>
    </div>
  </div>
</div>

</div>

<script>
const reservationLabels = {!! json_encode($reservationLabels) !!};
const reservationValues = {!! json_encode($reservationValues) !!};
const validees = {{ $validees }};
const rejetee = {{ $rejetee }};
const encours = {{ $encours }};
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ secure_asset('js/dashboard.js') }}"></script>

<!-- Alertes succès/erreur -->
@if(session('success'))
  <script> showSuccess(@json(session('success'))); </script>
@endif
@if(session('error'))
  <script> showError(@json(session('error'))); </script>
@endif

<script>
// pagination

const input = document.querySelector('.goto-page input');
const maxPage = {{ $reservations->lastPage() }};

// Empêche les valeurs invalides en temps réel
input.addEventListener('input', function() {
    let value = parseInt(this.value);
    if (isNaN(value) || value < 1) {
        this.value = 1;
    } else if (value > maxPage) {
        this.value = maxPage;
    }
});

// Navigation quand on appuie sur Enter
input.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        const page = parseInt(this.value);
        if (page >= 1 && page <= maxPage) {
            const url = new URL(window.location.href);
            url.searchParams.set('page', page); // remplace ou ajoute le paramètre page
            window.location.href = url.toString();
        } else {
            alert('Page invalide !');
        }
    }
});
</script>


