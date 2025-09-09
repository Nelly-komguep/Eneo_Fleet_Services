<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard Utilisateur</title>

    <!-- CSS principal -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css"/>
    <link rel="stylesheet" href="{{ asset('css/dashboard_user.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- ================= Sidebar ================= -->
    <div class="sidebar">
        <h2>MENU</h2>
        <ul>
            <li class="menu-item active"><i class="ri-dashboard-line"></i> Dashboard</li>
        </ul>

        <!-- Profil utilisateur -->
        <div class="admin-profile">
            <div class="avatar"></div>
            <div class="admin-info">
                <strong>{{ Auth::user()->name }} ({{ Auth::user()->role }})</strong>
                <a href="{{ route('logout')}}" class="logout-link">Se déconnecter</a>
            </div>
        </div>
    </div>

    <!-- ================= Main Content ================= -->
    <div class="main">

        <!-- Header avec bouton pour nouvelle réservation -->
        <div class="header">
            <h1>Eneo Fleet Service</h1>
            <div class="buttons-group">
                <!-- Bouton ouverture modal réservation -->
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reservationModal">
       <i class="ri-add-large-fill"></i> Faire une réservation
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

        <!-- ================= Cartes Statistiques ================= -->
        <div class="cards">
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

        <!-- ================= Tableau Réservations ================= -->
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
                    <tr>
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

                        <!-- Actions Modifier / Supprimer -->
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-success btn-sm editBtn"
                                    data-id="{{ $reservation->id }}"
                                    data-type_reservation="{{ $reservation->type_reservation }}"
                                    data-date_depart="{{ $reservation->date_depart }}"
                                    data-date_arrivee="{{ $reservation->date_arrivee }}"
                                    data-lieu_depart="{{ $reservation->lieu_depart }}"
                                    data-lieu_arrive="{{ $reservation->lieu_arrive }}"
                                    data-nombre_places="{{ $reservation->nombre_places }}"
                                    data-liste_passagers="{{ $reservation->liste_passagers }}"
                                    data-motif="{{ $reservation->motif }}"
                                    data-ordre_mission="{{ $reservation->ordre_mission }}"
                                    data-bs-toggle="modal" data-bs-target="#editModal">
                                    Modifier
                                </button>

                                <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST" onsubmit="return confirm('Supprimer ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-delete btn-sm btn-danger">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $reservations->links('pagination::bootstrap-5') }}
            </div>
        </div>

        <!-- ================= Modal Modifier Réservation ================= -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST" id="editForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-content">

                        <!-- Header Modal -->
                        <div class="modal-header">
                            <h5 class="modal-title">Modifier Réservation</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>

                        <!-- Body Modal -->
                        <div class="modal-body row g-3">
                            <input type="hidden" name="id" id="edit-id">

                            <div class="col-md-6">
                                <label>Type</label>
                                <input type="text" name="type_reservation" id="edit-type" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label>Date Départ</label>
                                <input type="date" name="date_depart" id="edit-depart" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label>Date Arrivée</label>
                                <input type="date" name="date_arrivee" id="edit-arrivee" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label>Lieu Départ</label>
                                <input type="text" name="lieu_depart" id="edit-lieu-depart" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label>Lieu Arrivée</label>
                                <input type="text" name="lieu_arrive" id="edit-lieu-arrive" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label>Nombre de places</label>
                                <input type="number" name="nombre_places" id="edit-places" class="form-control">
                            </div>

                            <div class="col-md-12">
                                <label>Liste des passagers</label>
                                <textarea name="liste_passagers" id="edit-passagers" class="form-control"></textarea>
                            </div>

                            <div class="col-md-6">
                                <label>Motif</label>
                                <input type="text" name="motif" id="edit-motif" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label>Ordre de mission</label>
                                <input type="text" name="ordre_mission" id="edit-ordre" class="form-control">
                            </div>
                        </div>

                        <!-- Footer Modal -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div> 


<!-- Fond assombri -->
<div id="deleteBackdrop" class="backdrop d-none"></div>

<!-- Modal suppression -->
<div id="deleteModal" class="modal-delete d-none" role="dialog" aria-modal="true">
    <div class="modal-icon" aria-hidden="true">
        <!-- Icône corbeille -->
        <svg viewBox="0 0 24 24" width="56" height="56">
            <path d="M9 3h6a1 1 0 0 1 1 1v1h4a1 1 0 1 1 0 2h-1.1l-1.2 12.2A3 3 0 0 1 14.7 23H9.3a3 3 0 0 1-2.99-2.8L5.1 7H4a1 1 0 1 1 0-2h4V4a1 1 0 0 1 1-1Zm6 2V4H9v1h6ZM7.1 7l1.17 11.8c.08.83.77 1.2 1.03 1.2h5.4c.26 0 .95-.37 1.03-1.2L16.9 7H7.1ZM10 9a1 1 0 0 1 1 1v7a1 1 0 1 1-2 0v-7a1 1 0 0 1 1-1Zm4 0a1 1 0 0 1 1 1v7a1 1 0 1 1-2 0v-7a1 1 0 0 1 1-1Z"/>
        </svg>
    </div>

    <h2 id="modal-title">Êtes-vous sûr de vouloir supprimer cette réservation&nbsp;?</h2>

    <div class="actions">
        <button id="cancelDelete" class="btn btn-ghost" type="button">Annuler</button>
        <button id="confirmDelete" class="btn btn-danger" type="button">Supprimer</button>
    </div>
</div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/dashboard-user.js') }}"></script>

    <!-- Affichage des messages de session -->
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

</body>
</html>
