<!-- resources/views/pages/my_events.blade.php -->

@extends('layouts.app')

@section('content')
<section class="my-events-container">
    @if ($events->count() > 0)
    <h1>My <span>Events</span></h1>
    <section class="my-events-cards">
        @foreach ($events as $event)
        <div class="my-event-card">
            @if ($event->images->isNotEmpty())
                    <div class="event-image" style="background-image: url('{{ \App\Http\Controllers\FileController::get('event_image', $event->images->first()->event_image_id) }}');"></div>
                @else
                    <div class="event-image" style="background-image: url('{{ asset('media/event_image.jpg') }}');"></div>
                @endif
            <a href="{{ route('view-event', ['id' => $event->event_id]) }}" class="my-event-info">
                <p id="my-event-card-local">{{ $event->location }}</p>
                <p id="my-event-card-name">{{ $event->name }}</p>
                <p id="my-event-card-date">{!! $event->start_timestamp->format('H:i, F j') !!}<br></p>
                <p id="my-event-card-tickets">Tickets: {{ $event->getTotalSoldTickets() }}</p>
                <p id="my-event-card-revenue">Total: {{ $event->calculateRevenue() }}€</p>
            </a>
        </div>
        @endforeach
        <div class="my-event-card new-my-event-card">
            <a href="{{ route('create-event') }}">
                <div class="new-event-info">
                    <h3>Create a new Event</h3>
                    <i class="fa-solid fa-circle-plus fa-4x" aria-label="Plus" ></i>
                </div>
            </a>
        </div>
    </section>
</section>
@else
<section class="warning-section">
    <i class="fa-solid fa-question" aria-label="Question" ></i>
    <p>You haven't created any events yet...</p>
    <a href="{{ route('create-event') }}" class="auth-link">Create your first event</a>
</section>
@endif
</div>
@endsection