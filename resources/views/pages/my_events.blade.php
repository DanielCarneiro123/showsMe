<!-- resources/views/pages/my_events.blade.php -->

@extends('layouts.app')  

@section('content')
    <h1>My Events</h1>

    @if ($events->count() > 0)
        @foreach ($events as $event)
            <article class="event-card">
                <h3>{{ $event->name }}</h3>
                <p>{{ $event->description }}</p>
            </article>
        @endforeach
    @else
        <p>No events found.</p>
    @endif

    <!-- Button to create a new event -->
    <a href="{{ route('create-event-page') }}" class="btn btn-primary">Create Event</a>

@endsection
