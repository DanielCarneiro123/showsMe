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
                <form class="login-form validate-form" method="POST" action="{{ route('login') }}">
                    @csrf

                    <span class="login-form-title">User Login</span>

                    <div class="input-wrapper field">
                        <ion-icon class="icon" name="person"></ion-icon>
                        <input class="input" type="email" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus>
                    </div>

                    <div class="input-wrapper">
                        <ion-icon class="icon" name="lock-closed"></ion-icon>
                        <input class="input" type="password" name="password" placeholder="Password" required>
                    </div>

                    <div class="btn-wrapper">
                        <button type="submit" class="login-btn">Login</button>
                    </div>

                    <div class="alt-methods">
                        <hr class="line-before">
                        <span> Or Login Using </span>
                        <hr class="line-after">
                    </div>

                    <div class="login-options">
                        <button class="google-btn">
                            <ion-icon class="opt-icon google" name="logo-google"></ion-icon>
                            <span class="option google">Google</span>
                        </button>
                        <button class="phone-btn">
                            <ion-icon class="opt-icon phone" name="call"></ion-icon>
                            <span class="option phone">Phone</span>
                        </button>
                    </div>

                    <div class="toggle-login">
                        <span> Don't have an account? 
                            <a href="{{ route('register') }}" class="toggle-register">Register </a> 
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
