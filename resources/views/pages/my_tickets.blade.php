

<!-- pages/my_tickets.blade.php -->

@extends('layouts.app')


@section('content')
    <h1>My <span>Tickets</span></h1>

    <section class="tickets-container">
        @foreach ($ticketInstances as $ticketInstance)
            <article class="ticket-instance">
                <h3><i class="fa-solid fa-ticket"></i>{{ $ticketInstance->ticketType->event->name }}</h3>
                <p>Location: {{ $ticketInstance->ticketType->event->location }}</p>
                <p>Ticket Type: {{ $ticketInstance->ticketType->name }}</p>
                <p>Start Time: {{ $ticketInstance->ticketType->event->start_timestamp }}</p>
                <p>End Time: {{ $ticketInstance->ticketType->event->end_timestamp }}</p>
                <img src="{{ route('ticket-verification', ['id' => $ticketInstance->ticket_instance_id]) }}" alt="QR Code">
            </article>
        @endforeach
    </section>
@endsection