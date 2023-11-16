<!-- pages/allevents.blade.php -->

@extends('layouts.app')  

@section('content')
    <!-- Add links before All Events -->
    <div>
        <a href="{{ route('faq') }}">FAQs</a> |
        <a href="{{ route('my-events') }}">MyEvents</a> |
        <a href="{{ route('my-tickets') }}">MyTickets</a> |
        <a href="{{ route('about-us') }}">About Us</a>
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

    <!-- Add the login button -->
    <div>
        <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
    </div>
@endsection
