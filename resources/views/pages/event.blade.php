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
    </section>
</section>

<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
  <input type="radio" class="btn-check" name="sectionToggle" id="eventInfo" autocomplete="off" checked data-section-id="event-info">
  <label class="btn btn-outline-primary" for="eventInfo">Event Info</label>
  
  <input type="radio" class="btn-check" name="sectionToggle" id="ticketTypes" autocomplete="off" data-section-id="ticket-types">
  <label class="btn btn-outline-primary" for="ticketTypes">Ticket Types</label>
  @can('updateEvent', $event)
    <input type="radio" class="btn-check" name="sectionToggle" id="editEvent" autocomplete="off" data-section-id="edit-event">
    <label class="btn btn-outline-primary" for="editEvent">Edit Event</label>
    
    <input type="radio" class="btn-check" name="sectionToggle" id="createTicketType" autocomplete="off" data-section-id="create-ticket-type">
    <label class="btn btn-outline-primary" for="createTicketType">Create Ticket Type</label>
  @endcan
</div>

<section id="ticket-types" class="event-section">
    <h2>Ticket <span>Types</span></h2>
    <form method="POST" action="{{ url('/purchase-tickets/'.$event->event_id) }}">
        @csrf
        @guest
        <div id="checkout-section" class="auth-form" style="display: none;">
            <div class="text-center"><label for="name">Name</label></div>
            <div class="my-input-group">
                <div class="icon-input">
                    <i class="fas fa-user"></i>
                    <input id="name" type="text" placeholder="Type your name" name="name" value="{{ old('name') }}" required autofocus>
                </div>
                @if ($errors->has('name'))
                    <span class="error">
                        {{ $errors->first('name') }}
                    </span>
                @endif
            </div>

            <div class="text-center"><label for="email">E-mail</label></div>
            <div class="my-input-group">
                <div class="icon-input">
                    <i class="fas fa-envelope"></i>
                    <input id="email" type="email" placeholder="Type your email" name="email" value="{{ old('email') }}" required>
                </div>
                @if ($errors->has('email'))
                    <span class="error">
                        {{ $errors->first('email') }}
                    </span>
                @endif
            </div>

            <div class="text-center"><label for="phone">Phone Number</label></div>
            <div class="my-input-group">
                <div class="icon-input">
                    <i class="fas fa-phone"></i>
                    <input id="phone" type="tel" placeholder="Type your phone number" name="phone_number" value="{{ old('phone_number') }}" required pattern="[0-9]{9}">
                </div>
                @if ($errors->has('phone_number'))
                    <span class="error">
                        {{ $errors->first('phone_number') }}
                    </span>
                @endif
            </div>
        </div>
        @endguest
        <br>
        <div id="ticket-types-container">
        @foreach ($event->ticketTypes as $ticketType)
            <article class="ticket-type" id="ticket-type-{{$ticketType->ticket_type_id}}" data-max="{{ min($ticketType->person_buying_limit, $ticketType->stock) }}">
                <h3>{{ $ticketType->name }}</h3>
                <p id="stock_display_{{ $ticketType->ticket_type_id }}">Stock: {{ $ticketType->stock }}</p>
                <p id="event_description_{{ $ticketType->ticket_type_id }}">Description: {{ $ticketType->description }}</p>
                <p id="ticket_price_{{ $ticketType->ticket_type_id }}">Price: {{ $ticketType->price }} €</p>
                @if (auth()->user() && auth()->user()->user_id == $event->creator_id)
                    @csrf
                    <p>New Stock:
                    <input type="number" id="new_stock_{{ $ticketType->ticket_type_id }}" name="new_stock" value="{{ $ticketType->stock }}" required>
                    </p>
                    <button class="button-update-stock" onclick="updateStock({{ $ticketType->ticket_type_id }})" form="purchaseForm">Update Stock</button>
                @endif
                @if ($ticketType->stock > 0)
                    
                    <label class="quant" id ="label{{$ticketType->ticket_type_id}}" for="quantity_{{ $ticketType->ticket_type_id }}">Quantity:</label>
                    <input class="quant" id ="input{{$ticketType->ticket_type_id}}" type="number" id="quantity_{{ $ticketType->ticket_type_id }}" name="quantity[{{ $ticketType->ticket_type_id }}]" min="0" max="{{ min($ticketType->person_buying_limit, $ticketType->stock) }}">
                    
                @endif
            </article>
        @endforeach
        </div>
        <br>
        @auth
        <button type="submit" class="btn btn-success event-button" id="buy-button">
            <i class="fa-solid fa-credit-card"></i> Buy Tickets
        </button>
        @endauth
        @guest
        <button type="button" class="btn btn-success event-button" id="show-form" onclick="toggleCheckoutSection()">
            <i class="fa-solid fa-credit-card"></i> Buy Tickets
        </button>
        <button type="submit" class="btn btn-success event-button" id="buy-button" style="display: none;">
            <i class="fa-solid fa-credit-card"></i> Buy Tickets
        </button>
        @endguest
    </form>
</section>






    @if(auth()->user() && auth()->user()->is_admin)
        <button class="btn btn-success {{ $event->private ? 'active' : '' }}" id="activate-button" data-id="{{ $event->event_id }}">{{ $event->private ? 'Activate Event' : 'Deactivate Event' }}</button>
    @endif

    <!-- Edit Event form (displayed only for the event creator) -->
    @can('updateEvent', $event)
        <section id="edit-event" class="event-section">
            <h2>Edit <span>Event</span></h2>
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

        <section id="create-ticket-type" class = "event-section">
            <h2>Create <span> TicketType </span> </h2>
                <article class="">
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
        </section>

        <section id="event-stats">
            <h2>Estatísticas de Bilhetes</h2>
        </section>
        
@endcan

@endsection