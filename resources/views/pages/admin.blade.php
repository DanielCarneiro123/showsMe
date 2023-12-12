@extends('layouts.app')

@section('content')
    <h1>Admin</h1>
    <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
        <input type="radio" class="btn-check" name="sectionToggle" id="reportComments" autocomplete="off" checked data-section-id="report-comments">
        <label class="btn btn-outline-primary" for="reportComments">Reported Comments</label>

        <input type="radio" class="btn-check" name="sectionToggle" id="manageUsers" autocomplete="off" data-section-id="manage-users">
        <label class="btn btn-outline-primary" for="manageUsers">Activate/Deactivate Users</label>
    </div>

    <div class="admin-section" id="report-comments">
        <h2 id="reported_comments_header">Reported Comments</h2>
        <table class="table mx-auto">
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Text</th>
                    <th>Report reason</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reportedComments as $reportedComment)
                    <tr id="reported_comment_row_{{ $reportedComment->comment_id }}">
                        <td><a href="{{ route('view-event', ['id' => $reportedComment->event_id]) }}">
                            {{ $reportedComment->event_name }}
                        </a></td>
                        <td>{{ $reportedComment->text }}</td>
                        <td>{{ $reportedComment->type }}</td>
                        <td>
                            <i class="toggle-eye fa-solid fa-eye show-icon" id="show_{{ $reportedComment->comment_id }}" onclick="toggleAdminCommentVisibility('{{ $reportedComment->comment_id }}', 'public')" style="{{ $reportedComment->private ? 'display: inline-block;' : 'display: none;' }}"></i>
                            <i class="toggle-eye fa-solid fa-eye-slash hidden-icon" id="hidden_{{ $reportedComment->comment_id }}" onclick="toggleAdminCommentVisibility('{{ $reportedComment->comment_id }}', 'private')" style="{{ $reportedComment->private ? 'display: none;' : 'display: inline-block;' }}"></i>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="admin-section" id="manage-users">
        <div id="active_users_section">
            <h2 id="active_users_header">Active Users</h2>
            <table class="table mx-auto">
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

        <div id="inactive_users_section">
            <h2 id="inactive_users_header">Inactive Users</h2>
            <table class="table mx-auto">
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
    </div>
@endsection
