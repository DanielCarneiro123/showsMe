@extends('layouts.app')

@section('content')
<h1>My <span>Tickets</span></h1>

@foreach ($ticketInstances->groupBy('ticketType.event.name') as $eventName => $eventTickets)
<div class="my-tickets-container">

    <div class="my-tickets-event" style="background-image: url('{{ asset("media/event_image.jpg") }}');">
        <h2 id="my-tickets-event-title">{{ $eventName }}</h2>
        <p id="my-tickets-event-local">{{ $eventTickets->first()->ticketType->event->location }}</p>
        <p id="my-tickets-event-Sdate">{!! $eventTickets->first()->ticketType->event->start_timestamp->format('H:i, F j') !!}<br></p>
    </div>
    <div class="my-tickets-per-event">
        @foreach ($eventTickets as $index => $ticketInstance)
        <article class="ticket-instance">
            <div class="info_area">
                <p id="tipo">{{ $ticketInstance->ticketType->name }}</p>
                <p id="hora">{{ $ticketInstance->ticketType->event->start_timestamp->format('H:i') }}</p>
                <p id="descri">{{ $ticketInstance->ticketType->description }}</p>
                <p id="num">{{ $index + 1 }}</p>
                <p id="ticket-logo">show<span>s</span>me</p>
            </div>
            <div class="line"></div>
            <div class="qr_area">
                @if ($ticketInstance->qr_code_path)
                {!! QrCode::size(100)->generate($ticketInstance->qr_code_path) !!}
                @else
                {!! QrCode::size(100)->generate(Request::input('id', $ticketInstance->ticket_instance_id)) !!}
                @endif
            </div>
        </article>
        @endforeach
    </div>
    @if ($index > 0)
    <button class="my-tickets-btn my-tickets-btn-see-more">See more</button>
    <button class="my-tickets-btn my-tickets-btn-hidden" style="display: none;">Hide</button>
    @endif

</div>

@endforeach
@endsection
