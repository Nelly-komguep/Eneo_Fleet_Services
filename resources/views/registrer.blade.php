<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
   <link rel="stylesheet" href="{{ asset('css/register.css')}}">
   <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Registrer</title>
</head>
<body>

  <div class="circle big1 top-left"></div>
  <div class="circle big2 bottom-left"></div>
  <div class="circle big top-right"></div>
  <div class="circle small random1"></div>
  <div class="circle medium random2"></div>
  <div class="circle small1 random3"></div>
  <div class="circle small random4"></div>
  <div class="circle medium random5"></div>

 <div class="left">
     <img src="../Images/toyota 1.png" alt="voiture" class="deposee">
    </div>

  <div class="container">
   
    <div class="right">
      <h1> BIENVENUE</h1>
      <p>Creez votre compte</p>
      <form action="{{ url('/register') }}" method="POST" class="form">
            @csrf
        <div class="field">
          <label>Nom</label>
          <span class="icon" id="name-icon"><i class="ri-user-fill"></i></span>
          <input type="text" id="name" name="name" placeholder="Entrer votre nom" required>
          <span id="name-error"></span>
        </div>
        <div class="field">
          <label>Email</label>
          <span class="icon" id="email-icon"><i class="ri-mail-fill"></i></span>
          <input type="email" id="email" name="email" placeholder="exemple@gmail.com" required>
          <span id="email-error"></span>
        </div>
        <div class="field">
          <label>Mot de passe</label>
          <span class="icon" id="password-icon"><i class="ri-key-fill"></i></span>
          <input type="password" id="password" name="password" placeholder="........" required>
          <span id="password-error"></span>
        </div>
        <div class="field">
          <label>Confirmez le mot de passe</label>
          <span class="icon" id="confirm-icon"><i class="ri-key-fill"></i></span>
          <input type="password" id="confirm" name="password_confirmation" placeholder=".........." required>
          <span id="confirm-error"></span>
        </div>
        <div class="field">
          <label for="role">Role</label>
                <select id="role" name="role" required >
                <option value="user">Utilisateur</option>
                <option value="admin">Administrateur</option>
                <option value="superadmin">Super Administrateur</option>
                </select>
        </div>
        <div class="field">
          <label for="code">Code</label>
            <input type="text" id="code" name="code" required disabled />
        </div>
        <div class="field full">
              <!-- reCAPTCHA -->
    {!! NoCaptcha::display() !!}

    @if ($errors->has('g-recaptcha-response'))
        <span class="text-danger">{{ $errors->first('g-recaptcha-response') }}</span>
    @endif
        </div>

        <div class="field">
        <button type="submit">Enregistrer</button>
        <p>Avez vous deja un compte ? <a href="../" class="signin-text">Connectez vous</a></p>
        </div>

  <div class="field">
  <label>Métier(s)</label>
  <div id="job-selector" class="job-selector">
      <div class="job-chip" data-id="Plombier">Chauffeur</div>
      <div class="job-chip" data-id="Électricien">Administrateur</div>
      <div class="job-chip" data-id="Mécanicien">Electricien</div>
      <div class="job-chip" data-id="Chauffeur">Gestionnaire</div>
  </div>
  <input type="hidden" name="jobs" id="selected-jobs">
</div>

<!-- Zone d'affichage des métiers choisis -->
<div class="field">
  <label>Métiers choisis :</label>
  <div id="jobs-added" class="jobs-added"></div>
</div>

      </form>
      
    </div>
  </div>

   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <script src="{{ asset('js/register.js') }}"></script>
<script>
$(document).ready(function () {
    let selectedJobs = [];

    $(".job-chip").on("click", function () {
        let jobName = $(this).text().trim();

        if ($(this).hasClass("selected")) {
            $(this).removeClass("selected");
            selectedJobs = selectedJobs.filter(j => j !== jobName);
            $(`#jobs-added .added-chip[data-name='${jobName}']`).remove();
        } else {
            $(this).addClass("selected");
            selectedJobs.push(jobName);
            $("#jobs-added").append(`<div class="added-chip" data-name="${jobName}">${jobName}</div>`);
        }

        //  Mettre à jour le champ caché
        $("#selected-jobs").val(selectedJobs.join(","));
    });

    // S'assurer que le champ est toujours rempli avant l'envoi
    $("form").on("submit", function () {
        $("#selected-jobs").val(selectedJobs.join(","));
    });
});
// Vérification disponibilité Nom / Email avec AJAX
$("#name, #email").on("blur", function() {
    let field = $(this).attr("id");
    let value = $(this).val();
    let icon = $("#" + field + "-icon");
    let errorSpan = $("#" + field + "-error");  

    if (value.trim() === "") {
        icon.find("i").removeClass().addClass("ri-user-fill"); // ou ri-mail-fill selon le champ
        errorSpan.text(""); 
        return;
    }

    // Afficher le spinner Remixicon
    let iTag = icon.find("i");
    iTag.removeClass().addClass("ri-loader-4-line ri-spin text-secondary");
    errorSpan.text("");

    $.ajax({
        url: "/check-field",
        method: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            field: field,
            value: value
        },
        success: function(response) {
            if (response.exists) {
                // Afficher croix rouge
                if(field === "name") iTag.removeClass().addClass("ri-user-fill text-danger");
                else iTag.removeClass().addClass("ri-mail-fill text-danger");
                errorSpan.text("Ce " + (field === "email" ? "email" : "nom") + " est déjà utilisé.")
                         .css("color", "red");
            } else {
                // Afficher check vert
                if(field === "name") iTag.removeClass().addClass("ri-user-fill text-success");
                else iTag.removeClass().addClass("ri-mail-fill text-success");
                errorSpan.text("");
            }
        }
    });
});
</script>

<!-- Script JS reCAPTCHA -->
{!! NoCaptcha::renderJs() !!}
</body>
</html>