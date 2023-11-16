<!DOCTYPE html>
<html lang="en">
<head>
    <title>Ticketlane</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/auth.css') }}">
    <link rel="icon" type="image/png" href="../images/logo1.png">
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js" defer></script>
</head>
<body>
    <div class="limiter">
        <div class="login-container" style="background-image: url('../images/background.png');">
            <div class="login-wrapper">
                <form class="login-form validate-form" method="POST" action="{{ route('register') }}">
                    {{ csrf_field() }}

                    <span class="login-form-title">User Register</span>

                    <div class="input-wrapper field">
                        <ion-icon class="icon" name="person"></ion-icon>
                        <input class="input" type="text" name="name" placeholder="Full name" value="{{ old('name') }}" required autofocus>
                    </div>

                    <div class="input-wrapper field">
                        <ion-icon class="icon" name="person"></ion-icon>
                        <input class="input" type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                    </div>

                    <div class="input-wrapper field">
                        <ion-icon class="icon" name="lock-closed"></ion-icon>
                        <input class="input" type="password" name="password" placeholder="Password" required>
                    </div>

                    <div class="input-wrapper field">
                        <ion-icon class="icon" name="lock-closed"></ion-icon>
                        <input class="input" type="password" name="password_confirmation" placeholder="Confirm password" required>
                    </div>

                    <div class="terms">
                        <input type="checkbox" id="acceptTerms">
                        <label for="acceptTerms" class="round-checkbox"></label>
                        <span> Accept
                            <strong> Terms & Conditions</strong>
                        </span>
                    </div>

                    <div class="btn-wrapper">
                        <button class="register-btn"> Register </button>
                    </div>

                    <div class="toggle-register">
                        <span> Already registered?
                            <a href="{{ route('login') }}" class="toggle-register"> Login </a>
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
