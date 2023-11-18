@extends('layouts.app')  

@section('content')
    <h1>Admin</h1>
    <p>This is the admin page.</p>

    <h2>Active Users</h2>
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
                <tr>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->name }}</td>
                    <td>
                        <form action="{{ route('deactivateUser', ['id' => $user->user_id]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit">Deactivate</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Inactive Users</h2>
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
                <tr>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->name }}</td>
                    <td>
                        <form action="{{ route('activateUser', ['id' => $user->user_id]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit">Activate</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

