

@extends('layouts.app')

@section('content')
    <h1>Profile</h1>

    <p>Email: {{ $user->email }}</p>
    <p>Name: {{ $user->name }}</p>
    <p>Promotor Code: {{ $user->promotor_code }}</p>
    <p>Phone Number: {{ $user->phone_number }}</p>

    <!-- Edit Form -->
<form action="{{ route('update-profile') }}" method="POST" >
    @csrf


    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="{{ $user->name }}" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="{{ $user->email }}" required>
    
    <label for="promotor_code">Promotor Code:</label>
    <input type="text" id="promotor_code" name="promotor_code" value="{{ $user->promotor_code }}" >

    <label for="phone_number">Phone Number:</label>
    <input type="text" id="phone_number" name="phone_number" value="{{ $user->phone_number }}" required>

    <button type="submit">Save Changes</button>
</form>

@endsection
