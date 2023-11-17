<!-- resources/views/profile/index.blade.php -->

@extends('layouts.app')

@section('content')
    <h1>Profile</h1>

    <p>Email: {{ $user->email }}</p>
    <p>Name: {{ $user->name }}</p>
    <p>Promotor Code: {{ $user->promotor_code }}</p>
    <p>Phone Number: {{ $user->phone_number }}</p>

    <!-- Add more fields as needed -->
@endsection
