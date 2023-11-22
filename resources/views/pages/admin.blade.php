@extends('layouts.app')  

@section('content')
    <h1>Admin</h1>
    <p>This is the admin page.</p>

    <h2 id="active_users_header">Active Users</h2>
    <div id="active_users_section">
        <table>
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($activeUsers as $user)
                    <tr id="active_user_row_{{ $user->user_id }}">
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->name }}</td>
                        <td>
                            <button class="deactivate-btn" data-user-id="{{ $user->user_id }}">Deactivate</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <h2 id="inactive_users_header">Inactive Users</h2>
    <div id="inactive_users_section">
        <table>
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($inactiveUsers as $user)
                    <tr id="inactive_user_row_{{ $user->user_id }}">
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->name }}</td>
                        <td>  
                            <button class="activate-btn" data-user-id="{{ $user->user_id }}">Activate</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
