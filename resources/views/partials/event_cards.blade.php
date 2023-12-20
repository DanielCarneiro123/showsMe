<section class="all-events-container" id="event-cards-section">
    <section class="all-events-cards" >
        @foreach ($events as $event)
            <div class="event-card">
                <div class="event-image" style="background-image: url('{{ asset('media/event_image.jpg') }}');"></div>
                <a href="{{ route('view-event', ['id' => $event->event_id]) }}" class="event-info">
                    <p id="event-card-local">{{ $event->location }}</p>
                    <p id="event-card-name">{{ $event->name }}</p>
                    <p id="event-card-date">{!! $event->start_timestamp->format('H:i, F j') !!}<br></p>
                </a>
            </div>
        @endforeach
    </section>
    <section class="mt-4">
        {{ $events->links() }}
    </section>
</section>
