<!-- resources/views/pages/my_events.blade.php -->

@extends('layouts.app')  

@section('content')
    <h1>My Events</h1>

    @if ($events->count() > 0)
        @foreach ($events as $event)
            <div>
                <h3>{{ $event->name }}</h3>
                <p>{{ $event->description }}</p>
                <!-- Add more fields as needed -->
            </div>
        @endforeach
    @else
        <p>No events found.</p>
    @endif

    <!-- Button to create a new event -->
    <form method="POST" action="{{ url('/create-event') }}">
    @csrf
    <button type="submit" class="btn btn-primary">Create Event</button>
</form>

@endsection
