<div id="notification-container" class="notification-container" style="display: none; max-width: 450px;">
    <div class="notification-content">
        <h2>Notifications</h2>
        <hr>
        <div id="notifications-body">
            @foreach ($notifications as $notification)
                <div class="notification">
                    @if ($notification->event_id)
                        @if ($notification->notification_type === 'Event')
                            <a href="{{ route('view-event', ['id' => $notification->event_id]) }}" class="event-link" >
                                The event {{ $notification->event->name }} had some changes made. Check them out!
                                {{ $notification->timestamp }}
                                {{ $notification->event_id }}
                            </a>
                        @elseif ($notification->notification_type === 'Comment')
                            <a href="{{ route('view-event', ['id' => $notification->event_id]) }}" class="event-link">
                                A comment was made in the event {{ $notification->event->name }}.
                                {{ $notification->timestamp }}
                            </a>
                        @elseif ($notification->notification_type === 'Report')
                            <a href="{{ route('view-event', ['id' => $notification->event_id]) }}" >
                                A report on a comment was made in the event {{ $notification->event->name }}.
                                {{ $notification->timestamp }}
                            </a>
                        @endif
                        
                    @else
                        @if ($notification->notification_type === 'Event')
                            The event {{ $notification->event_id }} had some changes made. Check them out!
                            {{ $notification->timestamp }}
                        @elseif ($notification->notification_type === 'Comment')
                            A comment was made in the event {{ $notification->event_id }}.
                            {{ $notification->timestamp }}                      
                        @elseif ($notification->notification_type === 'Report')
                            A report on a comment was made in the event {{ $notification->event_id }}.
                            {{ $notification->timestamp }}
                        @endif
                    @endif
                    <hr>
                </div>
            @endforeach
        </div>
    </div>
</div>
