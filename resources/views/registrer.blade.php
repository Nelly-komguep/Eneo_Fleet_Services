<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
   <link rel="stylesheet" href="{{ asset('css/register.css')}}">
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
      </form>
      
    </div>
  </div>

   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <script src="{{ asset('js/register.js') }}"></script>

<!-- Script JS reCAPTCHA -->
{!! NoCaptcha::renderJs() !!}
</body>
</html>