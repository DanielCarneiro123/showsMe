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
    @include('partials.event_cards')
    
@endsection

