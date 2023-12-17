@extends('layouts.app')

@section('content')
<h1>Featured <span>Events</span></h1>

<section class="centered-section-fluid text-center mt-4 mx-auto">
    <form class="row justify-content-center" method="GET" action="{{ route('search-events') }}">
        <div class="col-sm-8 col-md-6 mb-3">
            <input class="form-control" type="text" name="query" placeholder="Search events...">
        </div>
        <div class="col-sm-4 col-md-2 mb-3">
            <button class="btn btn-primary btn-block" type="submit">Search</button>
        </div>
    </form>
</section>

<section class="all-events-container">
    <section class="all-events-cards">
        @foreach ($events as $event)
        <div class="event-card">
            <div class="event-image" style="background-image: url('{{ asset('media/event_image.jpg') }}');"></div>
            <a href="{{ route('view-event', ['id' => $event->event_id]) }}" class="event-info">
                <p id="event-card-local">{{ $event->location }}</p>
                <p id="event-card-name">{{ $event->name }}</p>
                <p id="event-card-date">{!! $event->start_timestamp->format('H:i, F j') !!}<br></p>
            </a>
        </div>
        @endforeach
    </section>
    {{ $events->links() }}
</section>


@endsection