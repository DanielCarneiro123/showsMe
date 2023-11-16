<!-- resources/views/pages/my_tickets.blade.php -->

@extends('layouts.app')  

@section('content')
    <h1>My Tickets</h1>

    @if ($ticketInstances->count() > 0)
        @foreach ($ticketInstances as $ticketInstance)
            <div>
                <p>Ticket Type: {{ $ticketInstance->ticketType->name }}</p>
                <p>Order Timestamp: {{ $ticketInstance->order->timestamp }}</p>
                <!-- Add more fields as needed -->
            </div>
        @endforeach
    @else
        <p>No tickets found.</p>
    @endif
@endsection
