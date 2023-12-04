<!-- pages/all_events.blade.php -->

@extends('layouts.app')  

@section('content')
    

    <h1>Featured <span>Events</span></h1>
    <section class="centered-section">
    <form  class="row" method="GET" action="{{ route('search-events') }}">
    <div class="col-sm-8">
    <input class="form-control me-sm-2" type="text" name="query" placeholder="Search events...">
    </div>
    <div class="col-sm-4">
    <button class="btn btn-primary" type="submit">Search</button>
        </div>
    </form>

    </section>
  

    
    <div class="event-container">
  @foreach ($events as $event)
    <div class="event-card">
      <a href="{{ route('view-event', ['id' => $event->event_id]) }}" class="event-link">
        
          <img src="{{ asset('../media/event_image.jpg') }}" alt="Event Image">
          <!-- <img src="{{ $event->event_image }}" alt="Event Image" class="event-image"> -->
          <div class="event-info">
            <h3>{{ $event->name }}</h3>
            <p>{{ $event->location }}</p>
          </div>
        </article>
      </a>
    </div>
  @endforeach
</div>

    
    {{ $events->links() }}



<!--    @if (Auth::check())
    <form action="{{ route('logout') }}" method="GET">
        @csrf
        <button type="submit" class="btn btn-danger">Logout</button>
    </form>
    Add the login button only if the user is not logged in 
    @else
    <div>
        <div>
            <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
        </div>
    </div>
    @endif -->
@endsection
