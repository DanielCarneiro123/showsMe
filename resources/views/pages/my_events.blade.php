<!-- resources/views/pages/my_events.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container text-center">
        @if ($events->count() > 0)
            <h1>My <span>Events</span></h1>
            <div class="row event-container">

                @foreach ($events as $event)
                    <div class="col-xs-12 col-sm-6 col-md-3 mb-3">
                        <a href="{{ route('view-event', ['id' => $event->event_id]) }}">
                            <article class="event-card">
                                <img src="{{ asset('../media/event_image.jpg') }}" alt="Event Image" class="img-fluid">
                                <div class="event-info">
                                    <h3>{{ $event->name }}</h3>
                                    <p>{{ $event->start_timestamp }}</p>
                                </div>
                            </article>
                        </a>
                    </div>
                @endforeach

                <div class="col-xs-12 col-sm-6 col-md-3 mb-3">
                    <a href="{{ route('create-event') }}">
                        <article class="event-card new-event-card">
                            <div class="new-event-info">
                                <h3>Create a new Event!</h3>
                                <i class="fa-solid fa-circle-plus fa-4x"></i>
                            </div>
                        </article>
                    </a>
                </div>

            </div>
        @else
            <section class="warning-section">
                <i class="fa-solid fa-question"></i>
                <p>You haven't created any events yet...</p>
                <a href="{{ route('create-event') }}" class="auth-link">Create your first event</a>
            </section>
        @endif
    </div>
@endsection
