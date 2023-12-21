<section class="my-events-cards">
    <div class="my-event-card new-my-event-card">
        <a href="{{ route('create-event') }}">
            <div class="new-event-info">
                <h3>Create a new Event</h3>
                <i class="fa-solid fa-circle-plus fa-4x" aria-label="Plus" ></i>
            </div>
        </a>
    </div>
    @foreach ($events as $event)
    <div class="my-event-card">
        <div class="event-image" style="background-image: url('{{ asset('media/event_image.jpg') }}');"></div>
        <a href="{{ route('view-event', ['id' => $event->event_id]) }}" class="my-event-info">
            <p id="my-event-card-local">{{ $event->location }}</p>
            <p id="my-event-card-name">{{ $event->name }}</p>
            <p id="my-event-card-date">{!! $event->start_timestamp->format('H:i, F j') !!}<br></p>
            <p id="my-event-card-tickets">Tickets: {{ $event->getTotalSoldTickets() }}</p>
            <p id="my-event-card-revenue">Total: {{ $event->calculateRevenue() }}€</p>
        </a>
    </div>
    @endforeach
</section>

<section class="mt-4"></section>

<section class="pagination mt-4 justify-content-center" id="pagination-links">
    {{ $events->links() }}
</section>
