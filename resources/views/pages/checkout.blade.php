@extends('layouts.app')

@section('content')

    {{-- Display checkout form for users --}}
    <form method="POST" action="{{ url('/purchase-tickets/'.$event->event_id) }}">
        @csrf
        <input type="hidden" id="selectedTickets" name="selectedTickets" value="">

        @guest
            {{-- Display additional fields for non-authenticated users --}}
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
        @endguest

        <div id="ticket-types-container">
        @foreach ($event->ticketTypes as $ticketType)
                @if ($ticketType->stock > 0)
                    <article class="ticket-type" id="ticket-type-{{$ticketType->ticket_type_id}}" data-max="{{ min($ticketType->person_buying_limit, $ticketType->stock) }}">
                        <h3>{{ $ticketType->name }}</h3>
                        <p id="event_description_{{ $ticketType->ticket_type_id }}">Description: {{ $ticketType->description }}</p>
                        <p id="ticket_price_{{ $ticketType->ticket_type_id }}">Price: {{ $ticketType->price }} €</p>
                        <label class="quant" id="label{{$ticketType->ticket_type_id}}" for="quantity_{{ $ticketType->ticket_type_id }}">Quantity:</label>
                        <input class="quant" id="input{{$ticketType->ticket_type_id}}" type="number" id="quantity_{{ $ticketType->ticket_type_id }}" name="quantity[{{ $ticketType->ticket_type_id }}]" min="0" max="{{ min($ticketType->person_buying_limit, $ticketType->stock) }}">
                    </article>
                @endif
            @endforeach
        </div>
        <br>
        <button type="submit" class="btn btn-success event-button" id="buy-button">
            <i class="fa-solid fa-credit-card"></i> Buy Tickets
        </button>
    </form>

@endsection
