<html lang="fr">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css">
<link rel="stylesheet" href="{{ asset('css/dashboardVehicule.css')}}">
<title>Dashboard Disponibilite</title>

</head>
<body>
  <!-- Sidebar -->
<div class="sidebar">
  <h2>MENU</h2>
  <ul>
    <li class="menu-item"><i class="ri-dashboard-line"></i>Dashboard</li>
    <a href="{{ route('dashboardVehicule') }}" style="text-decoration: none; color:aliceblue;"> <li class="menu-item active"><i class="ri-car-line"></i> Management des vehicules</li></a>
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
      <table>
        <thead>
          <tr>
            <th></th>
            <th>Marque</th>
            <th>Chauffeur</th>
            <th>Disponible</th>
            <th>Detail</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr class="row1">
            <td><input type="checkbox" /></td>
            <td style="display: flex; align-items: center; gap: 10px;">
              <img src="../Images/toyota 1.png" width="40" height="40" alt="logo_voiture" /> 
              <span>Toyota</span>
            </td>
            <td>ijedjwel</td>
            <td><button style="background:#00c4b4; border:none; padding:4px 8px; border-radius:4px; color:#fff;">Disponible</button></td>
            <td style="cursor:pointer; color: blue;">&rarr;</td>
            <td>
              <svg class="icon" viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.93l-3.75-3.75L3 17.25z"/></svg>
              <svg class="icon" viewBox="0 0 24 24"><path d="M3 13h18v-2H3v2zm0 4h18v-2H3v2zm0-8h18V7H3v2z"/></svg>
              <svg class="icon" viewBox="0 0 24 24"><path d="M6 19a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V7H6v12ZM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4Z"/></svg>
            </td>
          </tr>
          <tr class="row2">
             <td><input type="checkbox" /></td>
             <td style="display: flex; align-items: center; gap: 10px;"> <img src="../Images/mercedes-removebg-preview.png" width="40px" height="40px" alt="logo_voiture" /> <span>Mercedes</span> </td>
             <td>klsdjfklsd</td>
             <td><button style="background:#ffb300; border:none; padding:4px 8px; border-radius:4px; color:#fff;">Indisponible</button></td>
             <td style="cursor:pointer; color: blue;">&rarr;</td>
             <td> <svg class="icon" viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.93l-3.75-3.75L3 17.25z"/></svg> <svg class="icon" viewBox="0 0 24 24"><path d="M3 13h18v-2H3v2zm0 4h18v-2H3v2zm0-8h18V7H3v2z"/></svg> <svg class="icon" viewBox="0 0 24 24"><path d="M6 19a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V7H6v12ZM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4Z"/></svg> </td>
             </tr>
        </tbody>
      </table>
      {{-- <!-- Pagination -->
    <div class="d-flex justify-content-center mt-3">
        {{ $reservations->links('pagination::bootstrap-5') }}
    </div> --}}
    </div>
  </div>
  <script src="{{ asset('js/dashboardVehicule.js') }}"></script>
</body>
</html>
