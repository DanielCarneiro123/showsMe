<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Styles -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
        <link href="{{ asset('css/auth.css') }}" rel="stylesheet" >
        <link href="{{ asset('css/contact.css') }}" rel="stylesheet">
        <link href="{{ asset('css/event.css') }}" rel="stylesheet">
        <link href="{{ asset('css/faq.css') }}" rel="stylesheet">
        <link href="{{ asset('css/profile.css') }}" rel="stylesheet">
        <link href="{{ asset('css/login.css') }}" rel="stylesheet">
        <link href="{{ asset('css/header.css') }}" rel="stylesheet">
        <link href="{{ asset('css/my_ticket.css') }}" rel="stylesheet">
        <link href="{{ asset('css/create_event.css') }}" rel="stylesheet">
        <link rel="icon" type="image/png" href="{{ asset('images/logo1.png') }}">
        <script type="text/javascript">
            // Fix for Firefox autofocus CSS bug
            // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
        </script>
        
        <script type="text/javascript" src={{ url('js/app.js') }} defer>
        </script>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js" ></script>
    </head>
    <body>
        <main>
            <header>
                <a href="{{ url('/all-events') }}" id="logo">show<span>s</span>me</a>
                <nav id='header-nav'>
                    

                    @auth
                        <a href="{{ route('my-events') }}">MyEvents</a> 
                        <a href="{{ route('my-tickets') }}">MyTickets</a> 
                        <a href="{{ route('profile') }}">Profile</a> 
                    @endauth
                    <a href="{{ route('create-event') }}">Create Event</a> 
                    <a href="{{ route('faq') }}">FAQs</a> 
                    <a href="{{ route('about-us') }}">About Us</a> 
            
                    @if(auth()->user() && auth()->user()->is_admin)
                        <a href="{{ route('admin') }}">Admin</a> 
                    @endif
                
            
                </nav>
                <section id='header-buttons'>
                    @if (Auth::check())
                        <span>{{ Auth::user()->name}}</span>
                        <a class="button login" href="{{ url('/logout') }}"> Logout </a>
                    @else
                        <a class="button login" href="{{ url('/login') }}"> login </a>
                        <a class="button" id="register" href="{{ url('/register') }}"> register </a>
                    @endif
                </section>
            </header>
            @if(session('error'))
            <div class="alert alert-danger" style="color: red;">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success" style="color: green;">
                    {{ session('success') }}
                </div>
            @endif
            <section id="content">
                @yield('content')
            </section>
        </main>
    </body>
</html>