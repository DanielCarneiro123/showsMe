@extends('layouts.app')

@section('content')
    <h1 class="mt-4">Admin</h1>
    <div class="btn-group d-flex justify-content-center mt-3 mx-auto" role="group" aria-label="Basic radio toggle button group">
        <input type="radio" class="btn-check" name="sectionToggle" id="reportComments" autocomplete="off" checked data-section-id="report-comments">
        <label class="btn btn-outline-primary" for="reportComments">Reported Comments</label>

        <input type="radio" class="btn-check" name="sectionToggle" id="manageUsers" autocomplete="off" data-section-id="manage-users">
        <label class="btn btn-outline-primary" for="manageUsers">Activate/Deactivate Users</label>
    </div>


    <div class="admin-section text-center mt-4 mx-auto" id="report-comments">
        <h2 id="reported_comments_header">Reported Comments</h2>
        <div class="mt-4"></div>
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
                        <td class="d-flex justify-content-center">
                            @if($reportedComment->private)
                                <i class="toggle-eye fa-solid fa-eye show-icon" id="show_{{ $reportedComment->comment_id }}" onclick="toggleAdminCommentVisibility('{{ $reportedComment->comment_id }}', 'public')" style="{{ $reportedComment->private ? 'display: flex;' : 'display: none;' }}"></i>
                                <i class="toggle-eye fa-solid fa-eye-slash hidden-icon" id="hidden_{{ $reportedComment->comment_id }}" onclick="toggleAdminCommentVisibility('{{ $reportedComment->comment_id }}', 'private')" style="{{ $reportedComment->private ? 'display: none;' : 'display: flex;' }}"></i>
                            @else
                                <i class="toggle-eye fa-solid fa-eye show-icon" id="show_{{ $reportedComment->comment_id }}" onclick="toggleAdminCommentVisibility('{{ $reportedComment->comment_id }}', 'public')" style="{{ $reportedComment->private ? 'display: flex;' : 'display: none;' }}"></i>
                                <i class="toggle-eye fa-solid fa-eye-slash hidden-icon" id="hidden_{{ $reportedComment->comment_id }}" onclick="toggleAdminCommentVisibility('{{ $reportedComment->comment_id }}', 'private')" style="{{ $reportedComment->private ? 'display: none;' : 'display: flex;' }}"></i>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p>Total de usuários: {{ $userCount }}</p>
        <p>Total de eventos: {{ $eventCount }}</p>
        <p>Total de eventos ativos: <span id="activeEventCount">{{ $activeEventCount }}</span></p>
        <p>Total de eventos inativos: <span id="inactiveEventCount">{{ $inactiveEventCount }}</span></p>

        <h2>Event Count by Month</h2>
        <p>Total de eventos no mês atual: <span id="eventCountByMonth">{{ $eventCountByMonth }}</span></p>

        <h2>Event Count by Day</h2>
        <p>Total de eventos no dia atual: <span id="eventCountByDay">{{ $eventCountByDay }}</span></p>

        <h2>Event Count by Year</h2>
        <p>Total de eventos no ano atual: <span id="eventCountByYear">{{ $eventCountByYear }}</span></p>
    </div>

    <div class="admin-section text-center mt-4 " id="manage-users">
        <div id="active_users_section mx-auto">
            <h2 id="active_users_header">Active Users</h2>
            <h3 id="activeUserCount">Total de usuários ativos: {{ count($activeUsers) }}</h3>
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

        <div id="inactive_users_section" class="mt-4 mx-auto">
            <h2 id="inactive_users_header">Inactive Users</h2>
            <h3 id="inactiveUserCount">Total de usuários inativos: {{ count($inactiveUsers) }}</h3>
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