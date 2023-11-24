

@extends('layouts.app')

@section('content')
<section id="profile">

            <i class="fa-regular fa-user"></i>
           

  
    <p id="user_email"><strong>Email: </strong>{{ $user->email }}</p>
    <p id="user_name"><strong>Name: </strong>{{ $user->name }}</p>
    <p id="user_promotor_code"><strong>Promotor Code:</strong> {{ $user->promotor_code }}</p>
    <p id="user_phone_number"><strong>Phone Number:</strong> {{ $user->phone_number }}</p>
<!--
<section>
    @csrf

    <label for="name">Name:</label>
    <input type="text" id="edit_name" name="name" value="{{ $user->name }}" required>
    @error('name')
        <span class="text-danger">{{ $message }}</span>
    @enderror

    <label for="email">Email:</label>
    <input type="email" id="edit_email" name="email" value="{{ $user->email }}" required>
    @error('email')
        <span class="text-danger">{{ $message }}</span>
    @enderror

    <label for="promotor_code">Promotor Code:</label>
    <input type="text" id="edit_promotor_code" name="promotor_code" value="{{ $user->promotor_code }}" >
    @error('promotor_code')
        <span class="text-danger">{{ $message }}</span>
    @enderror

    <label for="phone_number">Phone Number:</label>
    <input type="text" id="edit_phone_number" name="phone_number" value="{{ $user->phone_number }}" required>
    @error('phone_number')
        <span class="text-danger">{{ $message }}</span>
    @enderror
    <br>
        <button type="button" class="btn btn-outline-primary" id="update-profile-button" onclick="updateProfile()">Save Changes</button>
    </br>
</section>-->


@endsection
