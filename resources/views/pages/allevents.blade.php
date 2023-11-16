<!-- pages/allevents.blade.php -->

@extends('layouts.app')  

@section('content')
    <h1>All Events</h1>

    @foreach ($events as $event)
        <div>
            <h3>{{ $event->name }}</h3>
            <p>{{ $event->description }}</p>
            <!-- Add more fields as needed -->
        </div>
    @endforeach
@endsection
