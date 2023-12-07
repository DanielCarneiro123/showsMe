<div id="notification-container" class="notification-container" style="display: none;">
            <div class="notification-content">
                <h2>Notifications</h2>
                <div id="notifications-body">
                    @foreach ($notifications as $notification)
                        <div class="notification">
                            @if ($notification->event_id)
                                <a href="{{ route('view-event', ['id' => $notification->event_id]) }}">
                                    {{ $notification->notification_type }}
                                    {{ $notification->timestamp }}
                                </a>
                            @else
                                <!-- Lógica adicional ou tratamento caso o event_id não esteja disponível -->
                                {{ $notification->notification_type }}
                                {{ $notification->timestamp }}
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>