<!-- pages/allevents.blade.php -->

@extends('layouts.app')  

@section('content')
    <!-- Add links before All Events -->
    

    <h1>Featured <span>Events</span></h1>

    @foreach ($events as $event)
    <a href="{{ route('view-event', ['id' => $event->event_id]) }}" class="event-link">
        <article class="event-card">
            <img src="{{ asset('../media/event_image.jpg') }}" alt="Event Image">
            <!-- <img src="{{ $event->event_image }}" alt="Event Image" class="event-image"> -->
            <div class="event-info">
                <h3>{{ $event->name }}</h3>
                <p>{{ $event->description }}</p>
                <!-- Add more fields as needed -->
            </div>
        </article>
    </a>
    @endforeach
    {{ $events->links() }}



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
