<!-- resources/views/pages/create_event_form.blade.php -->

@extends('layouts.app')  

@section('content')
    <h1>Create Event</h1>

    <form method="POST" action="{{ url('/create-event') }}">
        @csrf

        <label for="name">Event Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="description">Event Description:</label>
        <textarea id="description" name="description"></textarea>

        <label for="location">Event Location:</label>
        <input type="text" id="location" name="location">

        <label for="start_timestamp">Start Timestamp:</label>
        <input type="datetime-local" id="start_timestamp" name="start_timestamp" required>

        <label for="end_timestamp">End Timestamp:</label>
        <input type="datetime-local" id="end_timestamp" name="end_timestamp" required>

        <button type="submit" class="btn btn-primary">Create Event</button>
    </form>

@endsection
