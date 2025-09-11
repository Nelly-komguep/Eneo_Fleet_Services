<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css"/>
{{-- <link rel="stylesheet" href="{{ asset('css/styles.css')}}"> --}}
<title>Login Eneo Fleet Service</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    
  <div class="loader">
    <div></div>
    <p>Chargement<span class="dots"></span></p>
  </div>
  <div class="content">
<div class="circle big1 top-left"></div>
  <div class="circle big2 bottom-left"></div>
  <div class="circle big top-right"></div>
  <div class="circle small random1"></div>
  <div class="circle medium random2"></div>
  <div class="circle small1 random3"></div>
  <div class="circle small random4"></div>
  <div class="circle medium random5"></div>
  <main class="card">

    <section class="left" aria-label="Aperçu voiture"> 
      <div class="left-bottom">
        <div class="social" aria-label="Connexion sociale">
          {{-- <a href="{{ route('login.facebook') }}" title="Facebook"><i class="ri-facebook-fill"></i></a> --}}
          <a href="{{ route('login.twitter') }}" title="Twitter"><i class="ri-twitter-x-line"></i></a>
          <a href="{{ route('auth.google') }}" title="Google" class="btn-social google"><i class="ri-google-line"></i></a>
        </div>
        <div class="signup">
          Pas encore de compte? <br>
          <a href="../register" style="margin-left: 40px;"   title="Create account">Inscrivez vous</a>
        </div>
    </div>
    </section>

    <section class="right">
      <div class="brand" aria-hidden="false">
         <i class="ri-car-fill"></i>
      </div>
      <div class="title">Eneo Fleet Services</div>
      <form action="{{ route('login') }}" method="POST">
       @csrf
        <div class="field">
          <label for="name"><i class="ri-user-line"> Nom </i></label>
          <input class="input" id="name" name="name" type="text" placeholder="Entrer votre nom" autocomplete="username" />
        </div>

        <div class="field">
          <label for="password"><i class="ri-key-fill"> Mot de passe </i></label>
          <input class="input" id="password" name="password" type="password" placeholder="........." autocomplete="current-password" />
        </div>

        <div class="row">
          <label class="checkbox">
            <input type="checkbox" id="remember" name="remember"/>
            <span>Se souvenir de moi</span>
          </label>
        </div>

        <button class="btn" type="submit">CONNEXION</button>
        <div class="helper" aria-hidden="true">&nbsp;</div>
        <a href="#" class="paragraphe"><p>Mot de passe oublie ?</p></a>
      </form>
    </section>

  </main>
  </div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/login.js') }}"></script>

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