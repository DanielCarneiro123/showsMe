@extends('layouts.app')

@section('content')
<section id="checkout">
    <h2>Checkout</h2>

    <form method="POST" action="{{ route('payment') }}">
        @csrf
        <div id="checkout-items-container" class="text-center">
            @foreach ($checkoutItems as $checkoutItem)
                <article class="checkout-item" id="checkout-item-{{ $checkoutItem['ticketType']->ticket_type_id }}"
                        data-max="{{ min($checkoutItem['ticketType']->person_buying_limit, $checkoutItem['ticketType']->stock) }}">
                    <h3>{{ $checkoutItem['eventName'] }}</h3>
                    <h4>{{ $checkoutItem['ticketType']->name }}</h4>
                    <p id="stock_display_{{ $checkoutItem['ticketType']->ticket_type_id }}">
                        Stock: {{ $checkoutItem['ticketType']->stock }}
                    </p>
                    <p id="event_description_{{ $checkoutItem['ticketType']->ticket_type_id }}">
                        Description: {{ $checkoutItem['ticketType']->description }}
                    </p>
                    <p id="ticket_price_{{ $checkoutItem['ticketType']->ticket_type_id }}">
                        Price: {{ $checkoutItem['ticketType']->price }} €
                    </p>

                    <label class="quant" id="label{{ $checkoutItem['ticketType']->ticket_type_id }}"
                        for="quantity_{{ $checkoutItem['ticketType']->ticket_type_id }}">Quantity:</label>
                    <input class="quant" id="input{{ $checkoutItem['ticketType']->ticket_type_id }}" type="number"
                        id="quantity_{{ $checkoutItem['ticketType']->ticket_type_id }}"
                        name="quantity[{{ $checkoutItem['ticketType']->ticket_type_id }}]"
                        min="0" max="{{ min($checkoutItem['ticketType']->person_buying_limit, $checkoutItem['ticketType']->stock) }}"
                        value="{{ $checkoutItem['quantity'] }}">

                </article>
            @endforeach
        </div>
        @guest
        <div id="checkout-section" class="auth-form" style="display: none;">
            <div class="text-center"><label for="name">Name</label></div>
            <div class="my-input-group">
                <div class="icon-input">
                    <i class="fas fa-user" aria-label="User" ></i>
                    <input id="name" type="text" placeholder="Type your name" name="name" value="{{ old('name') }}"
                        required autofocus>
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
                    <i class="fas fa-envelope" aria-label="Envelope" ></i>
                    <input id="email" type="email" placeholder="Type your email" name="email" value="{{ old('email') }}"
                        required>
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
                    <i class="fas fa-phone" aria-label="Phone" ></i>
                    <input id="phone" type="tel" placeholder="Type your phone number" name="phone_number"
                        value="{{ old('phone_number') }}" required pattern="[0-9]{9}">
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
        <div class="d-flex justify-content-center">
            @auth
            <button type="submit" class="btn btn-success checkout-button" id="checkout-button">
                <i class="fa-solid fa-credit-card" aria-label="Credit Card" ></i> Process Checkout
            </button>
            @endauth
            @guest
            <button type="button" class="btn btn-success event-button" id="show-form" onclick="toggleCheckoutSection()">
                <i class="fa-solid fa-cart-shopping" aria-label="Shopping Cart"></i> Process Checkout
            </button>
            <button type="submit" class="btn btn-success checkout-button" id="checkout-button" style="display: none;">
                <i class="fa-solid fa-credit-card" aria-label="Credit Card" ></i> Process Checkout
            </button>
            @endguest
        </div>
    </form>
</section>
@endsection
