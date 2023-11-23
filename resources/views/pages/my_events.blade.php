<!-- resources/views/pages/my_events.blade.php -->

@extends('layouts.app')  

@section('content')


    @if ($events->count() > 0)
        <h1>My <span>Events</span></h1>
        @foreach ($events as $event)
            <a href="{{ route('view-event', ['id' => $event->event_id]) }}">
            <article class="event-card">
            <img src="{{ asset('../media/event_image.jpg') }}" alt="Event Image">
                <div class="event-info">
                    <h3>{{ $event->name }}</h3>
                    <p>{{ $event->description }}</p>
                </div>
            </article>
            </a> 
        @endforeach
        <!-- Button to create a new event -->
        <a href="{{ route('create-event') }}" class="a-button">
            <i class="fa-solid fa-circle-plus"></i>
            Create Event
        </a>
    @else
        <section class="warning-section">
            <i class="fa-solid fa-question"></i>
            <p>You haven't created any events yet...</p>
            <a href="{{ route('create-event') }}" class="auth-link">Create your first event </a>
        </section>
    @endif

    <!-- Button to create a new event -->
    <a href="{{ route('create-event') }}" class="btn btn-primary">Create Event</a>

@endsection
