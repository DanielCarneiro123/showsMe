<!-- resources/views/events/view.blade.php -->

@extends('layouts.app')  <!-- Assuming you have a layout file -->

@section('content')
    <div>
        <h1>{{ $event->name }}</h1>
        <p>Date: {{ $event->location }}</p>
        <p>{{ $event->description }}</p>
        <!-- Add more fields as needed -->
    </div>
@endsection
