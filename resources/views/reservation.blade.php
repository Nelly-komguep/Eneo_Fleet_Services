<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Reservation</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css">
  <link rel="stylesheet" href="{{ asset('css/stylesReservation.css')}}">
</head>
<body>

<div class="top-banner">
  <div class="header">
    <div class="circle">
      <img src="../Images/logo-removebg-preview.png">
    </div>
  </div>
  <h1>Fleet Services.</h1>
  <div class="icons">
    <span title="Utilisateur" class="icon1"><i class="ri-user-follow-line">  Lorems </i></span>
    <span title="Réglages" class="icon2"><i class="ri-user-follow-line"></i></span>
  </div>
</div>

<div class="container">
  <article>
    <h2>Faire une réservation</h2>
  </article>

  <form id="reservationForm" method="POST" action="{{ route('reserve.store')}}">
    @csrf
    <div class="full-width" style="margin-top:10px;">
      <label for="type">Type de réservation</label>
      <select id="type" name="type_reservation" required>
        <option value="vehicule">Véhicule</option>
        <option value="vehicule_chauffeur">Véhicule_chauffeur</option>
        <option value="Chauffeur">Chauffeur</option>
      </select>
    </div>

    <div class="form-group">
      <label for="du">Du *</label>
      <input type="date" id="du" name="date_depart" required>
    </div>

    <div class="form-group">
      <label for="au">Au *</label>
      <input type="date" id="au" name="date_arrivee" required>
    </div>

    <div class="form-group">
      <label for="de">De *</label>
      <select id="de" name="lieu_depart" required>
        <option value="Lormes">Douala</option>
         <option value="Yaounde">Yaounde</option>
      </select>
    </div>

    <div class="form-group">
      <label for="a">A *</label>
      <select id="a" name="lieu_arrive" required>
        <option value="Lormes">Yaounde</option>
        <option value="Limbe">Limbe</option>
        <option value="Kribi">kribi</option>
    </div>

    <div class="form-group">
      <label for="places">Nombre de places</label>
      <input type="number" id="places" name="nombre_places" min="1">
    </div>

    <div class="full-width" style="margin-top:10px;">
      <label for="passagers">Liste des passagers</label>
      <input type="text" id="passagers" name="liste_passagers" placeholder="Saisir la liste des passagers">
    </div>

    <div class="full-width" style="margin-top:10px;">
      <label for="motif">Motif</label>
      <input type="text" id="motif" name="motif" placeholder="Saisir le motif de la réservation">
    </div>

    <div class="full-width" style="margin-top:10px;">
      <label>OM validé ? *</label>
      <div class="radio-group">
        <label><input type="radio" name="om" value="Oui" required> Oui</label>
        <label><input type="radio" name="om" value="Non"> Non</label>
      </div>
    </div>

    <div class="full-width" style="margin-top:10px;">
      <label for="ordre">Ordre de Mission :</label>
      <textarea id="ordre" name="ordre_mission" rows="3" style="width:100%; padding:8px; border-radius:4px; border:1px solid #ccc;"></textarea>
    </div>

    <button type="submit">ENVOYER</button>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/reservation.js') }}"></script>

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
  
</script>
</body>
</html>
