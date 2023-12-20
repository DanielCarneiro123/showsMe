<!-- resources/views/pages/create_event_form.blade.php -->

@extends('layouts.app')  

@section('content')

    @auth
        <h1>Create Event</h1>

        <div class="progress" id="progress-bar-container">
            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
        </div>

        <div class="mt-4"></div>
        
        <!-- Conteúdo do formulário de criação de evento para utilizadores autenticados -->
        <form method="POST" id="create-event-form" action="{{ url('/create-event') }}">
            @csrf

            <div class="form-group container-fluid">
                <label for="name" class="form-label mt-4">Event Name:</label>
                    <input type="text" class="form-control form-field" id="name" name="name" placeholder="Type the name of the event" required>
                </div>
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Event Description -->
            <div class="form-group">
                <label for="description" class="form-label mt-4">Event Description:</label>
                <textarea id="description" class="form-control form-field" placeholder="Type a short description of the event" name="description"></textarea>
                @error('description')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>


            <!-- Event Location -->
            <div class="form-group">
                <label for="location" class="form-label mt-4">Event Location:</label>
                <input type="text" class="form-control form-field" id="location" name="location" placeholder="Type the location of the event" required>
                @error('location')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Start Timestamp -->
            <div class="form-group">
                <label for="start_timestamp" class="form-label mt-4">Start Timestamp:</label>
                <input type="datetime-local" class="form-control form-field" name="start_timestamp" required>
                @error('start_timestamp')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- End Timestamp -->
            <div class="form-group">
                <label for="end_timestamp" class="form-label mt-4">End Timestamp:</label>
                <input type="datetime-local" class="form-control form-field" name="end_timestamp" required>
                @error('end_timestamp')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <button class="btn btn-primary" type="submit" >Create Event</button>

        </form>

    @else
        <!-- Mensagem para utilizadores não autenticados -->
        <div class="container">
            <div class="row">
            <section class="warning-section text-center">
                <i class="fa-solid fa-circle-exclamation fa-3x" aria-label="Circle" ></i>
                <p class="text-sm">Junta-te a nós e cria os teus eventos!</p>
                <p class="text-sm">Deves fazer <a href="{{ route('login') }}" class="auth-link">login </a> primeiro.</p>
            </section>
            </div>
        </div>

    @endauth
@endsection
