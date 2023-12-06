@extends('layouts.app')

@section('content')
<section class="auth-section">
    <form class="auth-form" method="POST" action="{{ route('login') }}">
        <h2>Login</h2>
        {{ csrf_field() }}
        <div class="text-center"><label for="email">Email</label></div>
        <div class="my-input-group">
           
     
            <div class="icon-input">
                <i class="fas fa-envelope"></i>
                <input id="email" type="email" placeholder="Type your email" name="email" value="{{ old('email') }}" required autofocus>
            </div>
      
            @if ($errors->has('email'))
                <span class="error">
                    {{ $errors->first('email') }}
                </span>
            @endif
        </div>
        <div class="text-center">
        <label for="password">Password</label>
        </div>
        <div class="my-input-group">
           
            <div class="icon-input">
                <i class="fas fa-lock"></i>
                <input id="password" type="password" placeholder="Type your password" name="password" required>
            </div>
            @if ($errors->has('password'))
                <span class="error">
                    {{ $errors->first('password') }}
                </span>
            @endif
        </div>
        
        <div class="text-center">
            <button class="btn btn-primary"type="submit">
            Login
        </button></div>
        
        <p class="auth-message">
            Don't have an account? <a href="{{ route('register') }}" class="text-primary">Register here</a>.
        </p>
        @if (session('success'))
            <p class="success">
                {{ session('success') }}
            </p>
        @endif
    </form>
</section>
@endsection
