@extends('layouts.app')  

@section('content')
    <h1>{{ $event->name }}</h1>
    <p>Location: {{ $event->location }}</p>
    <p>{{ $event->description }}</p>
    <!-- Add more fields as needed -->

 <!-- Display TicketTypes -->
 <h2>Ticket Types</h2>
<form method="POST" action="{{ url('/purchase-tickets/'.$event->event_id) }}">
    @csrf
    @foreach ($event->ticketTypes as $ticketType)
        <div>
            <h3>{{ $ticketType->name }}</h3>
            <p>Stock: {{ $ticketType->stock }}</p>
            <p>Description: {{ $ticketType->description }}</p>
            <p>Price: {{ $ticketType->price }} €</p>

            <!-- Adicione um campo de input para a quantidade desejada -->
            <label for="quantity_{{ $ticketType->ticket_type_id }}">Quantity:</label>
            <input type="number" id="quantity_{{ $ticketType->ticket_type_id }}" name="quantity[{{ $ticketType->ticket_type_id }}]" min="0" max="{{ $ticketType->stock }}">
        </div>
    @endforeach

    <!-- Adicione um botão geral para comprar -->
    <button type="submit" class="btn btn-success">Buy Tickets</button>
</form>





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
    @endcan
@endsection
