<!-- resources/views/pages/create_event_form.blade.php -->

@extends('layouts.app')  

@section('content')
    <h1>Create Event</h1>

    @auth
        <!-- Conteúdo do formulário de criação de evento para utilizadores autenticados -->
        <form method="POST" action="{{ url('/create-event') }}">
    @csrf
    <!-- Form fields -->
    <label for="name">Event Name:</label>
    <input type="text" id="name" name="name" required>
    @error('name')
        <span class="text-danger">{{ $message }}</span>
    @enderror

    <label for="description">Event Description:</label>
    <textarea id="description" name="description"></textarea>
    @error('description')
        <span class="text-danger">{{ $message }}</span>
    @enderror

    <label for="location">Event Location:</label>
    <input type="text" id="location" name="location" required>
    @error('location')
        <span class="text-danger">{{ $message }}</span>
    @enderror

    <label for="start_timestamp">Start Timestamp:</label>
    <input type="datetime-local" id="start_timestamp" name="start_timestamp" required>
    @error('start_timestamp')
        <span class="text-danger">{{ $message }}</span>
    @enderror

    <label for="end_timestamp">End Timestamp:</label>
    <input type="datetime-local" id="end_timestamp" name="end_timestamp" required>
    @error('end_timestamp')
        <span class="text-danger">{{ $message }}</span>
    @enderror

    <button type="submit" class="btn btn-primary">Create Event</button>
</form>

    @else
        <!-- Mensagem para utilizadores não autenticados -->
        <p>Junta-te a nós e cria os teus eventos!</p>
        <p>Deves fazer login primeiro.</p>
        <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
    @endauth
@endsection
