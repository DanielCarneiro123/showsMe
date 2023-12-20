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

        <br>
        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-success checkout-button" id="checkout-button">
                <i class="fa-solid fa-credit-card" aria-label="Credit Card" ></i> Process Checkout
            </button>
        </div>
    </form>
</section>
@endsection
