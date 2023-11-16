

<!-- pages/my_tickets.blade.php -->

@extends('layouts.app')

@section('content')
    <h1>My Tickets</h1>

    @foreach ($ticketInstances as $ticketInstance)
        <div>
            <p>Order ID: {{ $ticketInstance->order_id }}</p>
            <p>Event Name: {{ $ticketInstance->ticketType->event->name }}</p>
            <p>Location: {{ $ticketInstance->ticketType->event->location }}</p>
            <p>Ticket Type: {{ $ticketInstance->ticketType->name }}</p>
            <p>Start Time: {{ $ticketInstance->ticketType->event->start_timestamp }}</p>
            <p>End Time: {{ $ticketInstance->ticketType->event->end_timestamp }}</p>
            <!-- Add more fields as needed -->
        </div>
    @endforeach
@endsection
