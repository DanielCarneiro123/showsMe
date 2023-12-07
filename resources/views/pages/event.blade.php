@extends('layouts.app')  

@section('content')

<section class="event-thumbnail">
    @if(auth()->user() && auth()->user()->is_admin)
        @if ($event->private)
            <form method="POST" action="{{ url('/activate-event/'.$event->event_id) }}">
                @csrf
                <button class="event-button" id="activate-button" type="submit">
                    <i class="fa-solid fa-circle-check"></i> Activate Event
                </button>
            </form>
        @else
            <form method="POST" action="{{ url('/deactivate-event/'.$event->event_id) }}">
                @csrf
                <button class="event-button" id="deactivate-button" type="submit">
                    <i class="fa-solid fa-ban"></i> Deactivate Event
                </button>
            </form>
        @endif
    @endif
    <img src="{{ asset('../media/event_image.jpg') }}" alt="Event Image">
    <div class="text">
        <h1 id ="name" >{{ $event->name }}</h1>
        <p id ="description" >{{ $event->description }}</p>
        <section class="ratings-event">
        <p id="average-rating"> Rating: {{ $event->averageRating }} <span class="star-icon">★</span></p>
       
        </section>
    </div>
    <!-- <img src="{{ $event->event_image }}" alt="Event Image" class="event-image"> -->
    <section class="event-info">
        <p id="location">Location: {{ $event->location }}</p>
        
    </section>
</section>

<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
  <input type="radio" class="btn-check" name="sectionToggle" id="eventInfo" autocomplete="off" checked data-section-id="event-info">
  <label class="btn btn-outline-primary" for="eventInfo">Event Info</label>
  
  
  <input type="radio" class="btn-check" name="sectionToggle" id="ticketTypes" autocomplete="off" data-section-id="ticket-types">
  <label class="btn btn-outline-primary" for="ticketTypes">Ticket Types</label>
  @can('updateEvent', $event)
    <input type="radio" class="btn-check" name="sectionToggle" id="editEvent" autocomplete="off" data-section-id="edit-event">
    <label class="btn btn-outline-primary" for="editEvent">Edit Event</label>
    
    <input type="radio" class="btn-check" name="sectionToggle" id="createTicketType" autocomplete="off" data-section-id="create-ticket-type">
    <label class="btn btn-outline-primary" for="createTicketType">Create Ticket Type</label>
  @endcan
</div>

<section id="event-info" class="event-section">
@if(auth()->user())
 
            
    @if($userRating = $event->userRating())
        <p id="your-rating" class="mt-2">
        @if($userRating = $event->userRating())
        <p id="your-rating">
            Your Rating: {{ $userRating->rating }}
            <span class="star-icon">★</span>
        </p>
    @else
        <p id="your-rating" class="mt-2">
            Give your Rating
        </p>
    @endif
        </p>
    @else
        <p id="your-rating">
            Give your Rating
        </p>
    @endif
    <h2 class="text-primary">Comments</h2>

    @forelse($event->comments as $comment)
        <div class="comment" data-id="{{ $comment->comment_id}}">
        @if(auth()->check())
        
        <div class="comment-icons-container">
            <p class="comment-author">{{ $comment->author->name }}</p>
            <div>
            <i class="fa-solid fa-flag" onclick="showReportPopUp()"></i>
            @if(auth()->user()->user_id === $comment->author->user_id)
            <i class="fa-solid fa-pen-to-square"></i>
            @endif
            </div>
        </div>
        @endif
       
        <p class="comment-text">{{ $comment->text }}</p>
            
      

        </div>
       
   

    @empty
        <p>No comments yet.</p>
    @endforelse

    <div class="pop-up-report">
    <div class="report-section">
        <h3>Why are you reporting?</h3>
        
        <input type="hidden" name="comment_id" id="reportCommentId" value="0">
        <form action="{{ route('submitReport') }}" method="post">
            @csrf
            <textarea id="reportReason" class="report-textbox" name="reportReason" rows="4" placeholder="Enter your reason here"></textarea>
            <button type="submit" class="btn btn-primary">Submit Report</button>
        </form>
    </div>
    </div>
    <form id="newCommentForm" action="{{ route('submitComment') }}" method="post">
    @csrf
    <div class="comment new-comment">
        <textarea name="newCommentText" id="newCommentText" class="new-comment-textbox" rows="3" placeholder="Write a new comment"></textarea>
    </div>
    <input type="hidden" name="event_id"  value="{{$event->event_id}}">
    <button type="submit" class="btn btn-primary" id="submit-comment-button">Submit Comment</button>
</form>


    


       
@endif

</section>

<section id="ticket-types" class="event-section">
    <h2>Ticket <span>Types</span></h2>
    <form method="POST" action="{{ url('/purchase-tickets/'.$event->event_id) }}">
        @csrf
        <div id="ticket-types-container">
        @foreach ($event->ticketTypes as $ticketType)
            <article class="ticket-type" id="ticket-type-{{$ticketType->ticket_type_id}}" data-max="{{ min($ticketType->person_buying_limit, $ticketType->stock) }}">
                <h3>{{ $ticketType->name }}</h3>
                <p id="stock_display_{{ $ticketType->ticket_type_id }}">Stock: {{ $ticketType->stock }}</p>
                <p id="event_description_{{ $ticketType->ticket_type_id }}">Description: {{ $ticketType->description }}</p>
                <p id="ticket_price_{{ $ticketType->ticket_type_id }}">Price: {{ $ticketType->price }} €</p>
                @if (auth()->user() && auth()->user()->user_id == $event->creator_id)
                    @csrf
                    <p>New Stock:
                    <input type="number" id="new_stock_{{ $ticketType->ticket_type_id }}" name="new_stock" value="{{ $ticketType->stock }}" required>
                    </p>
                    <button class="button-update-stock" onclick="updateStock({{ $ticketType->ticket_type_id }})" form="purchaseForm">Update Stock</button>
                @endif
                @if ($ticketType->stock > 0)
                    
                    <label class="quant" id ="label{{$ticketType->ticket_type_id}}" for="quantity_{{ $ticketType->ticket_type_id }}">Quantity:</label>
                    <input class="quant" id ="input{{$ticketType->ticket_type_id}}" type="number" id="quantity_{{ $ticketType->ticket_type_id }}" name="quantity[{{ $ticketType->ticket_type_id }}]" min="0" max="{{ min($ticketType->person_buying_limit, $ticketType->stock) }}">
                    
                @endif
            </article>
        @endforeach
        </div>
        <br>
        <button type="submit" class="btn btn-success event-button"  id="buy-button">  
            <i class="fa-solid fa-credit-card"></i>Buy Tickets
        </button>
    </form>
</section>






    @if(auth()->user() && auth()->user()->is_admin)
        <button class="btn btn-success {{ $event->private ? 'active' : '' }}" id="activate-button" data-id="{{ $event->event_id }}">{{ $event->private ? 'Activate Event' : 'Deactivate Event' }}</button>
    @endif

    <!-- Edit Event form (displayed only for the event creator) -->
    @can('updateEvent', $event)
        <section id="edit-event" class="event-section">
            <h2>Edit <span>Event</span></h2>
            <article>
                @csrf
                <label for="edit_name">Event Name:</label>
                <input type="text" id="edit_name" name="edit_name" value="{{ $event->name }}" required>

                <label for="edit_description">Event Description:</label>
                <textarea id="edit_description" name="edit_description">{{ $event->description }}</textarea>

                <label for="edit_location">Event Location:</label>
                <input type="text" id="edit_location" name="edit_location" value="{{ $event->location }}" required>

                <label for="edit_start_timestamp">Start Timestamp:</label>
                <input type="datetime-local" id="edit_start_timestamp" name="edit_start_timestamp" value="{{ $event->start_timestamp }}" required>

                <label for="edit_end_timestamp">End Timestamp:</label>
                <input type="datetime-local" id="edit_end_timestamp" name="edit_end_timestamp" value="{{ $event->end_timestamp }}" required>
                @error('edit_end_timestamp')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                <button class="button-update-event" onclick="updateEvent({{ $event->event_id }})">Update Event</button>
            </article>
        </section>

        <section id="create-ticket-type" class = "event-section">
            <h2>Create <span> TicketType </span> </h2>
                <article class="">
                    @csrf

                    <label for="ticket_name">Ticket Name:</label>
                    <input type="text" id="ticket_name" name="ticket_name" required>

                    <label for="ticket_stock">Stock:</label>
                    <input type="number" id="ticket_stock" name="ticket_stock" required>

                    <label for="ticket_description">Ticket Description:</label>
                    <textarea id="ticket_description" name="ticket_description"></textarea>

                    <label for="ticket_person_limit">Person Buying Limit:</label>
                    <input type="number" id="ticket_person_limit" name="ticket_person_limit" required>

                    <label for="ticket_price">Ticket Price:</label>
                    <input type="number" id="ticket_price" name="ticket_price" required>

                    <label for="ticket_start_timestamp">Ticket Start Timestamp:</label>
                    <input type="datetime-local" id="ticket_start_timestamp" name="ticket_start_timestamp" required>

                    <label for="ticket_end_timestamp">Ticket End Timestamp:</label>
                    <input type="datetime-local" id="ticket_end_timestamp" name="ticket_end_timestamp" required>
                    @error('ticket_end_timestamp')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror            <!-- You might want to add more fields based on your requirements -->

                        <button type="button" class="btn btn-primary" onclick="createTicketType({{ $event->event_id }})">Create TicketType</button>
                </article>
        </section>
        
@endcan

@endsection
