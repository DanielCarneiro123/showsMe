<!-- resources/views/pages/create_event_form.blade.php -->

@extends('layouts.app')  

@section('content')

    @auth
        <h1>Create Event</h1>
        <!-- Conteúdo do formulário de criação de evento para utilizadores autenticados -->
        <form method="POST" id="create-event-form" action="{{ url('/create-event') }}">
            @csrf
            <!-- Form fields -->

            <!-- Event Name -->
            <div class="my-input-group">
                <label for="name">Event Name:</label>
                <div class="icon-input">
                    <i class="fas fa-tag"></i>
                    <input type="text" placeholder="Type the name of the event" name="name" required>
                </div>
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Event Description -->
            <div class="my-input-group">
                <label for="description">Event Description:</label>
                <div class="icon-input">
                    <i class="fas fa-file-alt"></i>
                    <textarea id="description" placeholder="Type a short description of the event" name="description"></textarea>
                </div>
                @error('description')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Event Location -->
            <div class="my-input-group">
                <label for="location">Event Location:</label>
                <div class="icon-input">
                    <i class="fas fa-map-marker-alt"></i>
                    <input type="text" placeholder="Type the location of the event" name="location" required>
                </div>
                @error('location')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Start Timestamp -->
            <div class="my-input-group">
                <label for="start_timestamp">Start Timestamp:</label>
                <div class="icon-input">
                    <i class="far fa-clock"></i>
                    <input type="datetime-local" name="start_timestamp" required>
                </div>
                @error('start_timestamp')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- End Timestamp -->
            <div class="my-input-group">
                <label for="end_timestamp">End Timestamp:</label>
                <div class="icon-input">
                    <i class="far fa-clock"></i>
                    <input type="datetime-local" name="end_timestamp" required>
                </div>
                @error('end_timestamp')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit">Create Event</button>
        </form>

    @else
        <!-- Mensagem para utilizadores não autenticados -->
        <section class="warning-section">
            <i class="fa-solid fa-circle-exclamation"></i>
            <p>Junta-te a nós e cria os teus eventos!</p>
            <p>Deves fazer <a href="{{ route('login') }}" class="auth-link">login </a> primeiro.</p>
        </section>
    @endauth
@endsection
