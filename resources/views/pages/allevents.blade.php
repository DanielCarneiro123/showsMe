<!-- pages/allevents.blade.php -->

@extends('layouts.app')  

@section('content')
    <!-- Add links before All Events -->
    <div>
        <a href="{{ route('faq') }}">FAQs</a> |
        <a href="{{ route('my-events') }}">MyEvents</a> |
        <a href="{{ route('my-tickets') }}">MyTickets</a> |
        <a href="{{ route('about-us') }}">About Us</a> |
        @if(auth()->user() && auth()->user()->is_admin)
            <a href="{{ route('admin') }}">Admin</a> |
        @endif
        <a href="{{ route('profile') }}">Profile</a>
    </div>

    <h1>All Events</h1>

    @foreach ($events as $event)
        <div>
            <a href="{{ route('view-event', ['id' => $event->event_id]) }}">
                <h3>{{ $event->name }}</h3>
            </a>
            <p>{{ $event->description }}</p>
            <!-- Add more fields as needed -->
        </div>
    @endforeach
    {{ $events->links()}}

<!--    @if (Auth::check())
    <form action="{{ route('logout') }}" method="GET">
        @csrf
        <button type="submit" class="btn btn-danger">Logout</button>
    </form>
    Add the login button only if the user is not logged in 
    @else
    <div>
        <div>
            <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
        </div>
    </div>
    @endif -->
@endsection
