<!-- resources/views/pages/my_events.blade.php -->

@extends('layouts.app')

@section('content')
<section class="my-events-container">
    @if ($events->count() > 0)
    <h1>My <span>Events</span></h1>
    <section class="my-events-cards">
        @foreach ($events as $event)
        <div class="my-event-card">
            <div class="event-image" style="background-image: url('{{ asset('media/event_image.jpg') }}');"></div>
            <a href="{{ route('view-event', ['id' => $event->event_id]) }}" class="my-event-info">
                <p id="my-event-card-local">{{ $event->location }}</p>
                <p id="my-event-card-name">{{ $event->name }}</p>
                <p id="my-event-card-date">{!! $event->start_timestamp->format('H:i, F j') !!}<br></p>
            </a>
        </div>
        @endforeach
        <div class="my-event-card new-my-event-card">
            <a href="{{ route('create-event') }}">
                    <div class="new-event-info">
                        <h3>Create a new Event</h3>
                        <i class="fa-solid fa-circle-plus fa-4x"></i>
                    </div>
            </a>
        </div>
    </section>

</section>
@else
<section class="warning-section">
    <i class="fa-solid fa-question"></i>
    <p>You haven't created any events yet...</p>
    <a href="{{ route('create-event') }}" class="auth-link">Create your first event</a>
</section>
@endif
</div>
@endsection