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
        <p>Create your first event.</p>
    @endif

    <!-- Button to create a new event -->
    <a href="{{ route('create-event-page') }}" class="btn btn-primary">Create Event</a>

@endsection
