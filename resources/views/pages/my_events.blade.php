<!-- resources/views/pages/my_events.blade.php -->

@extends('layouts.app')  

@section('content')


    @if ($events->count() > 0)
        <h1>My Events</h1>
        @foreach ($events as $event)
            <article class="event-card">
                <h3>{{ $event->name }}</h3>
                <p>{{ $event->description }}</p>
            </article>
        @endforeach
        <!-- Button to create a new event -->
        <a href="{{ route('create-event-page') }}" class="btn btn-primary">Create Event</a>
    @else
        <section class="warning-section">
            <i class="fa-solid fa-question"></i>
            <p>You haven't created any events yet...</p>
            <a href="{{ route('create-event-page') }}" class="auth-link">Create your first event </a>
        </section>
    @endif



@endsection
