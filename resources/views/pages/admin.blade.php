<script type="text/javascript" src={{ url('js/admin_paginate.js') }} defer></script>

@extends('layouts.app')

@section('content')
<div id="tab_bar" class="container-fluid">
    <div class="d-flex justify-content-center text-center">
        <div class="btn-group d-flex justify-content-center mt-3 mx-auto" role="group"
            aria-label="Basic radio toggle button group">
            <input type="radio" class="btn-check" name="sectionToggle" id="reportComments" autocomplete="off" checked
                data-section-id="report-comments">
            <label class="btn btn-outline-primary" for="reportComments">Manage Reports</label>

            <input type="radio" class="btn-check" name="sectionToggle" id="manageUsers" autocomplete="off"
                data-section-id="manage-users">
            <label class="btn btn-outline-primary" for="manageUsers">Manage Users</label>

            <input type="radio" class="btn-check" name="sectionToggle" id="adminStats" autocomplete="off"
                data-section-id="admin-stats">
            <label class="btn btn-outline-primary" for="adminStats">Admin Stats</label>
        </div>
    </div>
</div>



<div class="admin-section text-center mt-4 mx-auto" id="report-comments">
    <h2 id="reported_comments_header">Reported Comments</h2>
    <div class="mt-4"></div>
    <table class="table mx-auto">
        <thead>
            <tr>
                <th>Event</th>
                <th>Report reason</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reportedComments as $reportedComment)
            <tr id="reported_comment_row_{{ $reportedComment->comment_id }}"
                data-report-id="{{ $reportedComment->comment_id }}">
                <td><a href="{{ route('view-event', ['id' => $reportedComment->event_id]) }}">
                        {{ $reportedComment->event_name }}
                    </a></td>
                <td>{{ $reportedComment->type }}</td>
                <td data-report-id="{{ $reportedComment->report_id }}">
                    <i class="fa-solid fa-xmark delete-report" aria-label="X"
                        data-report-id="{{ $reportedComment->report_id }}"></i>
                    <i class="fa-solid fa-trash" aria-label="Delete" onclick="confirmAdminDeleteComment()"></i>
                    <form id="confirmAdminDeleteCommentForm" style="display: none;">
                        <p class="text-danger">Are you sure you want to delete the reported comment?</p>
                        <button class="btn btn-danger"
                            onclick="deleteAdminComment(event, {{ $reportedComment->comment_id }})">Delete</button>
                        <button type="button" class="btn btn-primary"
                            onclick="hideAdminDeleteCommentModal()">Cancel</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

    <div class="admin-section text-center mt-4 flex-container" id="manage-users">
        <div id="active_users_section" class="mt-4 mx-auto table-container">
            <h2 id="active_users_header">Active Users</h2>
            <h3 id="activeUserCount">Total Active Users: {{ count($activeUsers) }}</h3>
            <table class="table mx-auto" id="activeUsersTable">
                @include('partials.active_table')
            </table>
        </div>

        <div id="inactive_users_section" class="mt-4 mx-auto table-container">
            <h2 id="inactive_users_header">Inactive Users</h2>
            <h3 id="inactiveUserCount">Total Inactive Users: {{ count($inactiveUsers) }}</h3>
            <table class="table mx-auto" id="inactiveUsersTable">
                @include('partials.inactive_table')
            </table>
        </div>
    </div>
    <div class="admin-section text-center" id="admin-stats">
        <br>
        <h2>Event Count by Month</h2>
        <p>Total de eventos no mês atual: <span id="eventCountByMonth">{{ $eventCountByMonth }}</span></p>

            <div class="admin-stats-cards">
                <p>This Month</p>
                <span id="eventCountByMonth">{{ $eventCountByMonth }}</span>
            </div>

            <div class="admin-stats-cards">
                <p>This Year</p>
                <span id="eventCountByYear">{{ $eventCountByYear }}</span>
            </div>
        </div>


    </section>


    <section id="other_stats">

        <h3>Some Stats</h3>

        <div id="cards">
            <div class="admin-stats-cards">
                <p>Users</p>
                <span id="userCount">{{ $userCount }}</span>
            </div>

            <div class="admin-stats-cards">
                <p>Events</p>
                <span id="eventCount">{{ $eventCount }}</span>
            </div>

            <div class="admin-stats-cards">
                <p>Active Events</p>
                <span id="activeEventCount">{{ $activeEventCount }}</span>
            </div>

            <div class="admin-stats-cards">
                <p>Inactive Events</p>
                <span id="inactiveEventCount">{{ $inactiveEventCount }}</span>
            </div>
        </div>


    </section>

</section>


@endsection