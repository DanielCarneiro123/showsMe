@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center">Featured <span>Events</span></h1>

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

        <div class="mt-4"></div>

        <div class="row event-container-fluid text-center d-flex">
            @foreach ($events as $event)
                <div class="col-xs-4 col-sm-3 mb-3">
                    <div class="event-card">
                        <a href="{{ route('view-event', ['id' => $event->event_id]) }}" class="event-link">
                            <img src="{{ asset('../media/event_image.jpg') }}" alt="Event Image" class="img-responsive">
                            <div class="event-info">
                                <h3>{{ $event->name }}</h3>
                                <p>{{ $event->location }}</p>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row container-fluid">
            <div class="col-md-12 text-center">
                {{ $events->links() }}
            </div>
        </div>
    </div>
@endsection
