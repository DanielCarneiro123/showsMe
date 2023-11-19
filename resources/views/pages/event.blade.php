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
        @if (auth()->user() && auth()->user()->user_id = $event->creator_id)
            <!-- Display all tickets for the event creator -->
            <div>
                <h3>{{ $ticketType->name }}</h3>
                <p>Stock: {{ $ticketType->stock }}</p>
                <p>Description: {{ $ticketType->description }}</p>
                <p>Price: {{ $ticketType->price }} €</p>
                <form method="POST" action="{{ url('/update-ticket-stock/'.$ticketType->ticket_type_id) }}">
                    @csrf
                    @method('PATCH')
                    <label for="new_stock">New Stock:</label>
                    <input type="number" id="new_stock" name="new_stock" value="{{ $ticketType->stock }}" required>
                    <button type="submit" class="btn btn-primary">Update Stock</button>
                </form>

                <label for="quantity_{{ $ticketType->ticket_type_id }}">Quantity:</label>
                <input type="number" id="quantity_{{ $ticketType->ticket_type_id }}" name="quantity[{{ $ticketType->ticket_type_id }}]" min="0" max="{{ min($ticketType->person_buying_limit, $ticketType->stock) }}">
                
                

            </div>
        @else
            <!-- Display tickets with stock greater than 0 for other users -->
            @if ($ticketType->stock > 0)
                <div>
                    <h3>{{ $ticketType->name }}</h3>
                    <p>Stock: {{ $ticketType->stock }}</p>
                    <p>Description: {{ $ticketType->description }}</p>
                    <p>Price: {{ $ticketType->price }} €</p>

                    <label for="quantity_{{ $ticketType->ticket_type_id }}">Quantity:</label>
                    <input type="number" id="quantity_{{ $ticketType->ticket_type_id }}" name="quantity[{{ $ticketType->ticket_type_id }}]" min="0" max="{{ min($ticketType->person_buying_limit, $ticketType->stock) }}">

                </div>
            @endif
        @endif
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
        <h2>Create TicketType</h2>
    <form method="POST" action="{{ url('/create-ticket-type/'.$event->event_id) }}">
        @csrf

        <label for="ticket_name">Ticket Name:</label>
        <input type="text" id="ticket_name" name="ticket_name" required>

        <label for="ticket_stock">Stock:</label>
        <input type="number" id="ticket_stock" name="ticket_stock" required>

        <label for="ticket_description">Ticket Description:</label>
        <textarea id="ticket_description" name="ticket_description"></textarea>

        <label for="ticket_person_limit">Person Buying Limit:</label>
        <input type="number" id="ticket_person_limit" name="ticket_person_limit" required>

        <label for="ticket_price">Ticket Price:</label>
        <input type="number" id="ticket_price" name="ticket_price" required>

        <label for="ticket_start_timestamp">Ticket Start Timestamp:</label>
    <input type="datetime-local" id="ticket_start_timestamp" name="ticket_start_timestamp" required>

    <label for="ticket_end_timestamp">Ticket End Timestamp:</label>
    <input type="datetime-local" id="ticket_end_timestamp" name="ticket_end_timestamp" required>


        <!-- You might want to add more fields based on your requirements -->

        <button type="submit" class="btn btn-primary">Create TicketType</button>
    </form>
@endcan

@endsection
