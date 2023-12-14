@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center">Featured <span>Events</span></h1>

        <section class="centered-section">
            <form class="row justify-content-center" method="GET" action="{{ route('search-events') }}">
                <div class="col-sm-8 col-md-6 mb-3">
                    <input class="form-control" type="text" name="query" placeholder="Search events...">
                </div>
                <div class="col-sm-4 col-md-2">
                    <button class="btn btn-primary btn-block" type="submit">Search</button>
                </div>
            </form>
        </section>

        <div class="row event-container">
            @foreach ($events as $event)
                <div class="col-sm-8 col-md-3 mb-3">
                    <div class="event-card">
                        <a href="{{ route('view-event', ['id' => $event->event_id]) }}" class="event-link">
                            <img src="{{ asset('../media/event_image.jpg') }}" alt="Event Image" class="img-fluid">
                            <div class="event-info">
                                <h3>{{ $event->name }}</h3>
                                <p>{{ $event->location }}</p>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row">
            <div class="col-md-12">
                {{ $events->links() }}
            </div>
        </div>
    </div>
@endsection
