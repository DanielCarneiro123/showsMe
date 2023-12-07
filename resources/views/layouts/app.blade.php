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
        <link href="{{ asset('css/header.css') }}" rel="stylesheet">
       
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
   
        <link href="{{ asset('css/auth.css') }}" rel="stylesheet" >
        <link href="{{ asset('css/contact.css') }}" rel="stylesheet">
        <link href="{{ asset('css/event.css') }}" rel="stylesheet">
        <link href="{{ asset('css/faq.css') }}" rel="stylesheet">
        <link href="{{ asset('css/profile.css') }}" rel="stylesheet">
        <link href="{{ asset('css/login.css') }}" rel="stylesheet">
        <link href="{{ asset('css/header.css') }}" rel="stylesheet">
        <link href="{{ asset('css/my_ticket.css') }}" rel="stylesheet">
        <link href="{{ asset('css/create_event.css') }}" rel="stylesheet">
        <link href="{{ asset('../css/pagination.css') }}" rel="stylesheet">
        <link href="{{ asset('../css/edit_event.css') }}" rel="stylesheet">
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
        <link rel="icon" type="image/png" href="{{ asset('images/logo1.png') }}">
        <script type="text/javascript">
            // Fix for Firefox autofocus CSS bug
            // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
        </script>
        
        <script type="text/javascript" src={{ url('js/app.js') }} defer>
        </script>
    </head>
    <body>
        <main>
        <nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ url('/all-events') }}" id="logo">show<span>s</span>me</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarColor01">
                <ul class="navbar-nav me-auto">
                @auth   
                <li class="nav-item">
                   <a class="nav-link" href="{{ route('my-events') }}">MyEvents</a> 
                </li> 
                <li class="nav-item">  
                   <a class="nav-link" href="{{ route('my-tickets') }}">MyTickets</a> 
                </li> 
                
                @endauth
                <li class="nav-item">
                   <a class="nav-link" href="{{ route('create-event') }}">Create Event</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('faq') }}">FAQs</a> 
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('about-us') }}">About Us</a> 
                </li>
            
                @if(auth()->user() && auth()->user()->is_admin)
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin') }}">Admin</a> 
                </li>
                @endif   
                </ul>
                <section id='header-buttons'>
                    @if (Auth::check())
                        <a id="user-header-name" class="text-light"  href="{{ route('profile') }}">{{ Auth::user()->name}}</a>
                        <a class="btn btn-outline-secondary" href="{{ url('/logout') }}"> Logout </a>
                    @else
                        <a class="btn btn-primary" href="{{ url('/login') }}"> Login </a>
                        <a class="btn btn-primary" href="{{ url('/register') }}"> Register </a>
                    @endif
                </section>
                </div>
            </div>
        </nav>

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