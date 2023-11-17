<!-- resources/views/pages/event.blade.php -->

@extends('layouts.app')  

@section('content')
    <h1>{{ $event->name }}</h1>
    <p>Location: {{ $event->location }}</p>
    <p>{{ $event->description }}</p>
    <!-- Add more fields as needed -->

    <!-- Display TicketTypes -->
    <!-- POST não funciona -->
    <h2>Ticket Types</h2>
    <form method="POST">
        @csrf
        @foreach ($event->ticketTypes as $ticketType)
            <div>
                <h3>{{ $ticketType->name }}</h3>
                <p>Stock: {{ $ticketType->stock }}</p>
                <p>Description: {{ $ticketType->description }}</p>
                <p>Price: {{ $ticketType->price }}</p>
                <!-- Add more fields as needed -->
                  </div>
        @endforeach
    </form>

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
@endsection
