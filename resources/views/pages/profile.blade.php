@extends('layouts.app')

@section('content')
<section id="profile">
    <i class="fa-regular fa-user"></i>

    <section>
        @csrf

        <label for="name">Name:</label>
        <input type="text" id="edit_name" name="name" value="{{ $user->name }}" required disabled>
        @error('name')
            <span class="text-danger">{{ $message }}</span>
        @enderror

        <label for="email">Email:</label>
        <input type="email" id="edit_email" name="email" value="{{ $user->email }}" required disabled>
        @error('email')
            <span class="text-danger">{{ $message }}</span>
        @enderror

        <label for="promotor_code">Promotor Code:</label>
        <input type="text" id="edit_promotor_code" name="promotor_code" value="{{ $user->promotor_code }}" disabled>
        @error('promotor_code')
            <span class="text-danger">{{ $message }}</span>
        @enderror

        <label for="phone_number">Phone Number:</label>
        <input type="text" id="edit_phone_number" name="phone_number" value="{{ $user->phone_number }}" required disabled>
        @error('phone_number')
            <span class="text-danger">{{ $message }}</span>
        @enderror

        <br>
        <button type="button" class="btn btn-outline-primary" id="edit-profile-button" onclick="toggleProfileButtons()">Edit Profile</button>
        <button type="button" class="btn btn-outline-primary" id="update-profile-button" onclick="updateProfile()" style="display: none;">Save Changes</button>
        </br>
    </section>
</section>


@endsection
