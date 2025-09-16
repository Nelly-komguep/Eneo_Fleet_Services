<html lang="fr">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css">
<link rel="stylesheet" href="{{ secure_asset('css/dashboardVehicule.css')}}">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Dashboard Disponibilite</title>

</head>
<body>
  <!-- Sidebar -->
<div class="sidebar">
  <h2>MENU</h2>
  <ul>
    <a href="{{ secure_url('dashboard') }}" style="text-decoration: none; color:aliceblue;"><li class="menu-item"><i class="ri-dashboard-line"></i>Dashboard</li></a>
    <li class="menu-item active"><i class="ri-car-line"></i> Management des vehicules</li>
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

  <!-- Main -->
  <div class="main">
    <div class="header">
      <h1>Eneo Fleet Services</h1>
      <div class="actions">
        <div class="filter">
          <select>
            <option>Tout</option>
            <option>Disponibles</option>
            <option>Indisponibles</option>
            <option>En reparation</option>
          </select>
        </div>
      </div>
    </div>

    <div class="search-box">
      <input type="text" placeholder="nom du vehicule...">
      <button class="btnRegistrar"><i class="ri-search-line"></i></button>
    </div>

    <div class="table-container">
      <button class="btnAjouter"><i class="ri-add-line"></i></button>
      <table id="vehiculesTable">
        {{-- <form id="vehiculeForm" class="form-step" action="{{ route('vehicules.store') }}" method="POST"> --}}
        <thead>
          <tr>
            <th></th>
            <th>Marque</th>
            <th>Model</th>
            <th>Chauffeur</th>
            <th>Statut</th>
            <th>Place totale</th>
            <th>Detail</th>
            <th>Action</th>
          </tr>
        </thead>
     
         <tbody>
  @foreach($vehicules as $vehicule)
  <tr>
    <td><input type="checkbox" /></td>
    <td style="display:flex; align-items:center; gap:10px;">
      <img src="{{ asset('Images/toyota 1.png') }}" width="40" height="40" alt="logo_voiture" />
      <span>{{ $vehicule->marque }}</span>
    </td>
    <td>{{ $vehicule->modele }}</td>
    <td>{{ $vehicule->chauffeur }}</td>
    <td>
      @if($vehicule->disponibilite == 'Disponible')
        <button style="background:#00c4b4; border:none; padding:4px 8px; border-radius:4px; color:#fff;">
          {{ $vehicule->disponibilite }}
        </button>
      @elseif($vehicule->disponibilite == 'Indisponible')
        <button style="background:#ffb300; border:none; padding:4px 8px; border-radius:4px; color:#fff;">
          {{ $vehicule->disponibilite }}
        </button>
      @else
        <button style="background:#ff0000; border:none; padding:4px 8px; border-radius:4px; color:#fff;">
          {{ $vehicule->disponibilite }}
        </button>
      @endif
    </td>
    <td>{{ $vehicule->places_total }}</td>
    <td style="cursor:pointer; color:blue;">&rarr;</td>
    <td>
      {{-- <svg class="icon" viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.93l-3.75-3.75L3 17.25z"/></svg> --}}
      <button class="btn-edit" data-id="{{ $vehicule->id }}" 
        data-marque="{{ $vehicule->marque }}"
        data-modele="{{ $vehicule->modele }}"
        data-chauffeur="{{ $vehicule->chauffeur }}"
        data-disponibilite="{{ $vehicule->disponibilite }}"
        data-places_total="{{ $vehicule->places_total }}">
    <i class="ri-edit-line"></i>
</button>

      {{-- <svg class="icon" viewBox="0 0 24 24"><path d="M3 13h18v-2H3v2zm0 4h18v-2H3v2zm0-8h18V7H3v2z"/></svg> --}}
      {{-- <svg class="icon" viewBox="0 0 24 24"><path d="M6 19a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V7H6v12ZM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4Z"/></svg> --}}
<button class="btn-delete" data-id="{{ $vehicule->id }}">
    <i class="ri-delete-bin-line"></i>
</button>

    </td>
  </tr>
  @endforeach
</tbody>
    
      </table>

<!-- Modal Ajouter Véhicule -->
<div id="vehiculeModal" class="modal" style="display:none;">
  <div class="modal-content">
    <h2>Ajouter un véhicule</h2>

    <form id="vehiculeForm" class="form-step" action="{{ route('vehicules.store.dashboard') }}" method="POST">
      @csrf

      <div class="form-group">
        <label>Marque</label>
        <input type="text" name="marque" placeholder="Ex: Toyota" required>
      </div>

      <div class="form-group">
        <label>Model</label>
        <input type="text" name="modele" placeholder="Ex: Corolla" required>
      </div>

      <div class="form-group">
        <label>Chauffeur</label>
        <input type="text" name="chauffeur" placeholder="Ex: Jean Dupont" required>
      </div>

      <div class="form-group">
        <label>Disponibilité</label>
        <div class="options">
          <label class="option">
            <input type="radio" name="disponibilite" value="Disponible" checked>
            <span>Disponible</span>
          </label>
          <label class="option">
            <input type="radio" name="disponibilite" value="Indisponible">
            <span>Indisponible</span>
          </label>
          <label class="option">
            <input type="radio" name="disponibilite" value="En réparation">
            <span>En réparation</span>
          </label>
        </div>
      </div>

      <div class="form-group two-cols">
        <div>
          <label>Places totales</label>
          <input type="number" name="places_total" placeholder="10" min="1" required>
        </div>
      </div> <!--  on ferme bien le two-cols -->

      <div class="form-actions">
        <button type="button" class="btn-cancel" onclick="closeModal()">Annuler</button>
        <button type="submit" class="btn-submit">Enregistrer</button>
      </div>
    </form>
  </div>
</div>
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div id="editVehiculeModal" class="modal"  style="display:none;">
  <div class="modal-content">
    <h2>Modifier le véhicule</h2>
    <form id="editVehiculeForm" method="POST"  method="POST" action="{{ route('vehicules.update', $vehicule->id) }}"> 
      @csrf
      @method('PUT')
      <input type="hidden" name="vehicule_id" id="editVehiculeId">
      <div class="form-group">
        <label>Marque</label>
        <input type="text" name="marque" id="editMarque" required>
      </div>
      <div class="form-group">
        <label>Model</label>
        <input type="text" name="modele" id="editModele" required>
      </div>
      <div class="form-group">
        <label>Chauffeur</label>
        <input type="text" name="chauffeur" id="editChauffeur" required>
      </div>
      <div class="form-group">
        <label>Disponibilité</label>
        <select name="disponibilite" id="editDisponibilite">
          <option value="Disponible">Disponible</option>
          <option value="Indisponible">Indisponible</option>
          <option value="En réparation">En réparation</option>
        </select>
      </div>
      <div class="form-group">
        <label>Places totales</label>
        <input type="number" name="places_total" id="editPlacesTotal" min="1" required>
      </div>
      <div class="form-actions">
        <button type="button" onclick="closeEditModal()">Annuler</button>
        <button type="submit">Enregistrer</button>
      </div>
    </form>
  </div>
</div>

<div id="deleteVehiculeModal" class="modal" style="display:none;">
  <div class="modal-content">
    <h3>Voulez-vous vraiment supprimer ce véhicule ?</h3>
    <form id="deleteVehiculeForm" method="POST">
      @csrf
      @method('DELETE')
      <input type="hidden" name="vehicule_id" id="deleteVehiculeId">
      <button type="button" onclick="closeDeleteModal()">Non</button>
      <button type="submit">Oui, supprimer</button>
    </form>
  </div>
</div>



      {{-- <!-- Pagination -->
    <div class="d-flex justify-content-center mt-3">
        {{ $reservations->links('pagination::bootstrap-5') }}
    </div> --}}
    </div>
  </div>
  <script>

document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('vehiculeModal');
  const openBtn = document.querySelector('.btnAjouter');
  const form = document.getElementById('vehiculeForm');
  const tbody = document.querySelector('#vehiculesTable tbody');

  // Ouvrir modal
  openBtn.addEventListener('click', () => {
    modal.style.display = 'flex';
  });

  // Fermer modal
  window.closeModal = function () {
    modal.style.display = 'none';
    form.reset();
  };

  // Charger les véhicules existants
  fetch('/vehicules')
    .then(res => res.json())
    .then(vehicules => {
      vehicules.forEach(v => appendVehicule(v));
    })
    .catch(err => console.error('Erreur chargement véhicules', err));

  // ✅ Submit du formulaire AJAX (modifié uniquement cette partie)
  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const fd = new FormData(form);

    try {
      const res = await fetch("{{ route('vehicules.store.dashboard') }}", {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Accept': 'application/json'
        },
        body: fd
      });

      const data = await res.json();

      if (!res.ok || !data.success) {
        alert(data.message || 'Erreur lors de l’enregistrement.');
        return;
      }

      appendVehicule(data.vehicule);
      alert('Véhicule ajouté avec succès !');
      closeModal();

    } catch (err) {
      console.error(err);
      alert('Erreur réseau.');
    }
  });

  // Fonction pour ajouter un véhicule à la table
  function appendVehicule(vehicule) {
    const color = vehicule.disponibilite === 'Disponible'   ? '#00c4b4' :
                  vehicule.disponibilite === 'Indisponible' ? '#ffb300' :
                                                              '#e74c3c';

    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td><input type="checkbox" /></td>
      <td style="display:flex;align-items:center;gap:10px;">
        <img src="../Images/toyota 1.png" width="40" height="40" alt="logo_voiture" />
        <span>${vehicule.marque}</span>
      </td>
      <td>${vehicule.modele}</td>
      <td>${vehicule.chauffeur}</td>
      <td>
        <button style="background:${color};border:none;padding:4px 8px;border-radius:4px;color:#fff;">
          ${vehicule.disponibilite}
        </button>
      </td>
      <td>${vehicule.places_restantes || vehicule.places_total}/${vehicule.places_total}</td>
      <td style="cursor:pointer;color:blue;">&rarr;</td>
      <td>
        <svg class="icon" viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.93l-3.75-3.75L3 17.25z"/></svg>
        <svg class="icon" viewBox="0 0 24 24"><path d="M3 13h18v-2H3v2zm0 4h18v-2H3v2zm0-8h18V7H3v2z"/></svg>
        <svg class="icon" viewBox="0 0 24 24"><path d="M6 19a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V7H6v12ZM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4Z"/></svg>
      </td>
    `;
    tbody.appendChild(tr);
  }
});






</script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="{{ secure_asset('js/dashboardVehicule.js') }}"></script>
</body>
</html>
