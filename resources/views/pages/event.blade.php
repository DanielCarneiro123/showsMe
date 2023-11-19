@extends('layouts.app')  

@section('content')

    <section class="event-thumbnail">
        <img src="{{ asset('../media/event_image.jpg') }}" alt="Event Image">
        <div class="text">
            <h1>{{ $event->name }}</h1>
            <p>{{ $event->description }}</p>
        </div>
        <!-- <img src="{{ $event->event_image }}" alt="Event Image" class="event-image"> -->
        <section class="event-info">
            <p id="location">Location: {{ $event->location }}</p>
            <!-- Add more fields as needed -->
        </section>
    </section>



    <!-- Display TicketTypes -->
    <section class="ticket-types">
        <h2>Ticket <span>Types</span></h2>
        <form method="POST">
            @csrf
            @foreach ($event->ticketTypes as $ticketType)
                <article class="ticket-type">
                    <h3>{{ $ticketType->name }}</h3>
                    <p id="ticket-type-price">${{ $ticketType->price }}</p>
                    <p id="ticket-type-description">{{ $ticketType->description }}</p>
                    <p id="ticket-type-stock">Only {{ $ticketType->stock }} left!</p>
                </article>
            @endforeach
        </form>
    </section>

    @if(auth()->user() && auth()->user()->is_admin)
        @if ($event->private)
            <form method="POST" action="{{ url('/activate-event/'.$event->event_id) }}">
                @csrf
                <button type="submit" class="btn btn-success">Activate Event</button>
            </form>
        @else
            <form method="POST" action="{{ url('/deactivate-event/'.$event->event_id) }}">
                @csrf
                <button type="submit" class="btn btn-danger">Deactivate Event</button>
            </form>
        @endif
    @endif

    <!-- Edit Event form (displayed only for the event creator) -->
    @can('update', $event)
        <section class="edit-event">
            <h2>Edit Event</h2>
            <form method="POST" action="{{ url('/update-event/'.$event->event_id) }}">
                @csrf
                <label for="edit_name">Event Name:</label>
                <input type="text" id="edit_name" name="edit_name" value="{{ $event->name }}" required>

                <label for="edit_description">Event Description:</label>
                <textarea id="edit_description" name="edit_description">{{ $event->description }}</textarea>

                <label for="edit_location">Event Location:</label>
                <input type="text" id="edit_location" name="edit_location" value="{{ $event->location }}">

                <label for="edit_start_timestamp">Start Timestamp:</label>
                <input type="datetime-local" id="edit_start_timestamp" name="edit_start_timestamp" value="{{ $event->start_timestamp }}" required>

                <label for="edit_end_timestamp">End Timestamp:</label>
                <input type="datetime-local" id="edit_end_timestamp" name="edit_end_timestamp" value="{{ $event->end_timestamp }}" required>

                <button type="submit" class="btn btn-primary">Update Event</button>
            </form>
        </section>
    @endcan
@endsection
