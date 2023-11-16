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
                <label for="quantity_{{ $ticketType->ticket_type_id }}">Quantity:</label>
                <input type="number" name="ticket_types[{{ $ticketType->ticket_type_id }}]" id="quantity_{{ $ticketType->ticket_type_id }}" min="0" max=10>
            </div>
        @endforeach

        <button type="submit">Purchase Tickets</button>
    </form>
@endsection
