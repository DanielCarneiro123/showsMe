@extends('layouts.app')

@section('content')
    <div class="container text-center">
        <h1>My <span>Tickets</span></h1>

        <div class="row tickets-container">
            @foreach ($ticketInstances as $ticketInstance)
                <div class="col-xs-12 col-sm-6 col-md-4 mb-3">
                    <article class="ticket-instance">
                        <h3 class="purple-text"><i class="fa-solid fa-ticket"></i>{{ $ticketInstance->ticketType->event->name }}</h3>
                        <p>Location: {{ $ticketInstance->ticketType->event->location }}</p>
                        <p>Ticket Type: {{ $ticketInstance->ticketType->name }}</p>
                        <p>Start Time: {{ $ticketInstance->ticketType->event->start_timestamp }}</p>
                        <p>End Time: {{ $ticketInstance->ticketType->event->start_timestamp }}</p>

                        @if ($ticketInstance->qr_code_path)
                            {!! QrCode::size(100)->generate($ticketInstance->qr_code_path) !!}
                        @else
                            {!! QrCode::size(100)->generate(Request::input('id', $ticketInstance->ticket_instance_id)) !!}
                        @endif
                    </article>
                </div>
            @endforeach
        </div>
    </div>
@endsection
