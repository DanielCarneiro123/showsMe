@extends('layouts.app')

@section('content')
<section class="auth-section">
    <form class="auth-form" method="POST" action="{{ route('login') }}">
        <h2>Login</h2>
        {{ csrf_field() }}

        <div class="input-group">
            <label for="email">E-mail</label>
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

        <div class="input-group">
            <label for="password">Password</label>
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

        <label>
            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
        </label>

        <button type="submit">
            Login
        </button>
        <p class="auth-message">
            Don't have an account? <a href="{{ route('register') }}" class="auth-link">Register here</a>.
        </p>
        @if (session('success'))
            <p class="success">
                {{ session('success') }}
            </p>
        @endif
    </form>
</section>
@endsection
