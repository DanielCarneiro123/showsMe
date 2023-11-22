@extends('layouts.app')  

@section('content')

    <section class="event-thumbnail">
        @if(auth()->user() && auth()->user()->is_admin)
            @if ($event->private)
                <form method="POST" action="{{ url('/activate-event/'.$event->event_id) }}">
                    @csrf
                    <button class="event-button" id="activate-button" type="submit">
                        <i class="fa-solid fa-circle-check"></i> Activate Event
                    </button>
                </form>
            @else
                <form method="POST" action="{{ url('/deactivate-event/'.$event->event_id) }}">
                    @csrf
                    <button class="event-button" id="deactivate-button" type="submit">
                        <i class="fa-solid fa-ban"></i> Deactivate Event
                    </button>
                </form>
            @endif
        @endif
        <img src="{{ asset('../media/event_image.jpg') }}" alt="Event Image">
        <div class="text">
            <h1 id ="name" >{{ $event->name }}</h1>
            <p id ="description" >{{ $event->description }}</p>
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
<form method="POST" action="{{ url('/purchase-tickets/'.$event->event_id) }}">
    @csrf
    <div id="ticket-types-container">
    @foreach ($event->ticketTypes as $ticketType)
        <article class="ticket-type">
            <h3>{{ $ticketType->name }}</h3>
            <p id="stock_display_{{ $ticketType->ticket_type_id }}">Stock: {{ $ticketType->stock }}</p>
            <p id="event_description_{{ $ticketType->ticket_type_id }}">Description: {{ $ticketType->description }}</p>
            <p id="ticket_price_{{ $ticketType->ticket_type_id }}">Price: {{ $ticketType->price }} €</p>
            @if (auth()->user() && auth()->user()->user_id == $event->creator_id)
                <!-- Display update form for the event creator -->
                @csrf
                <p>New Stock:
                <input type="number" id="new_stock_{{ $ticketType->ticket_type_id }}" name="new_stock" value="{{ $ticketType->stock }}" required>
                </p>
                <button class="button-update-stock" onclick="updateStock({{ $ticketType->ticket_type_id }})" form="purchaseForm">Update Stock</button>
            @endif
                <!-- Display buy form for other users -->
            @if ($ticketType->stock > 0)
                    @csrf
                    <label for="quantity_{{ $ticketType->ticket_type_id }}">Quantity:</label>
                    <input type="number" id="quantity_{{ $ticketType->ticket_type_id }}" name="quantity[{{ $ticketType->ticket_type_id }}]" min="0" max="{{ min($ticketType->person_buying_limit, $ticketType->stock) }}">
                    <!--<button type="submit" class="btn btn-success">Buy Tickets</button>-->
            @endif
        </article>
    @endforeach
    </div>
    <br>
    <!-- Adicione um botão geral para comprar -->
    <button type="submit" class="btn btn-success event-button"  id="buy-button">  <!-- OR class="event-button" ? -->
        <i class="fa-solid fa-credit-card"></i>Buy Tickets
    </button>
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
    @can('updateEvent', $event)
        <section class="edit-event">
            <h2>Edit Event</h2>
            <article>
                @csrf
                <label for="edit_name">Event Name:</label>
                <input type="text" id="edit_name" name="edit_name" value="{{ $event->name }}" required>

                <label for="edit_description">Event Description:</label>
                <textarea id="edit_description" name="edit_description">{{ $event->description }}</textarea>

                <label for="edit_location">Event Location:</label>
                <input type="text" id="edit_location" name="edit_location" value="{{ $event->location }}" required>

                <label for="edit_start_timestamp">Start Timestamp:</label>
                <input type="datetime-local" id="edit_start_timestamp" name="edit_start_timestamp" value="{{ $event->start_timestamp }}" required>

                <label for="edit_end_timestamp">End Timestamp:</label>
                <input type="datetime-local" id="edit_end_timestamp" name="edit_end_timestamp" value="{{ $event->end_timestamp }}" required>
                @error('edit_end_timestamp')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                <button class="button-update-event" onclick="updateEvent({{ $event->event_id }})">Update Event</button>
            </article>
        </section>
        
        <h2>Create TicketType</h2>
    <article>
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
        @error('ticket_end_timestamp')
            <span class="text-danger">{{ $message }}</span>
        @enderror            <!-- You might want to add more fields based on your requirements -->

            <button type="button" class="btn btn-primary" onclick="createTicketType({{ $event->event_id }})">Create TicketType</button>
    </article>
        
@endcan

@endsection
