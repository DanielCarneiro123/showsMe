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

    <!-- Slideshow container -->
    <div class="slideshow-container">
        @php $index = 1; @endphp

        @foreach ($event->images as $image)
            <div class="mySlides faded">
                <div class="numbertext">{{ $index }} / {{ count($event->images) }}</div>
                <img src="{{ \App\Http\Controllers\FileController::get('event_image', $event->event_id) }}" alt="Event Image">
            </div>
            @php $index++; @endphp
        @endforeach
            <div class="mySlides faded">
                <div class="numbertext">{{ $index }} / {{ count($event->images) }}</div>
                <img src="{{ asset('../media/event_image.jpg') }}" alt="Event Image">
            </div>
        <!-- Next and previous buttons -->
        <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
        <a class="next" onclick="plusSlides(1)">&#10095;</a>
    </div>
    <br>



    <div class="text">
        <h1 id ="name" >{{ $event->name }}</h1>
        <p id ="description" >{{ $event->description }}</p>
        <section class="ratings-event">
        <p id="average-rating"> Rating: {{ $event->averageRating }} <span class="star-icon">★</span></p>
       
        </section>
        <h1 id="name">{{ $event->name }}</h1>
        <p id="description">{{ $event->description }}</p>
    </div>
    <!-- <img src="{{ $event->event_image }}" alt="Event Image" class="event-image"> -->
    <section class="event-info">
        <p id="location">Location: {{ $event->location }}</p>
        
    </section>
</section>

<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
    <input type="radio" class="btn-check" name="sectionToggle" id="eventInfo" autocomplete="off" checked
        data-section-id="event-info">
    <label class="btn btn-outline-primary" for="eventInfo">Event Info</label>

    <input type="radio" class="btn-check" name="sectionToggle" id="ticketTypes" autocomplete="off"
        data-section-id="ticket-types">
    <label class="btn btn-outline-primary" for="ticketTypes">Ticket Types</label>
    @can('updateEvent', $event)
    <input type="radio" class="btn-check" name="sectionToggle" id="editEvent" autocomplete="off"
        data-section-id="edit-event">
    <label class="btn btn-outline-primary" for="editEvent">Edit Event</label>

    <input type="radio" class="btn-check" name="sectionToggle" id="createTicketType" autocomplete="off"
        data-section-id="create-ticket-type">
    <label class="btn btn-outline-primary" for="createTicketType">Create Ticket Type</label>
    @endcan
</div>

<section id="event-info" class="event-section">

 
@if(auth()->user())
    @if($userRating = $event->userRating())
    <p id="yourRatingP" class="text-center">
        Your Rating: {{ $userRating->rating }}
        <span class="star-icon">★</span>
        <button class="btn btn-primary" onclick="showEditRatingForm()">Edit</button>
    </p>
    <div class="centered-form">
    <form id="editRatingForm" class="rate" method="POST" action="{{ route('editRating', ['eventId' => $event->event_id]) }}" style="display: none;">
        @csrf
        
        
        <input type="radio" name="rate" id="star5" value="5" {{ $userRating->rating == 5 ? 'checked' : '' }}>
        <label for="star5" >5 stars</label>
   
    
        <input type="radio" name="rate" id="star4" value="4" {{ $userRating->rating == 4 ? 'checked' : '' }}>
        <label for="star4" >4 stars</label>
   
        <input type="radio" name="rate" id="star3" value="3" {{ $userRating->rating == 3 ? 'checked' : '' }}>
        <label for="star3" >3 stars</label>
    
        <input type="radio" name="rate" id="star2" value="2" {{ $userRating->rating == 2 ? 'checked' : '' }}>
        <label for="star2" >2 stars</label>    
      
        <input type="radio" name="rate" id="star1" value="1" {{ $userRating->rating == 1 ? 'checked' : '' }}>
        <label for="star1" >1 star</label>
   
        <br>
        <div class="text-center">
        <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>
    @else
    <p class="text-center rate"> Give us your Rating: </p>
    <div class="centered-form">
    <form id="ratingForm" class="rate" method="POST" action="{{ route('submitRating', ['eventId' => $event->event_id]) }}">
    @csrf
    
        <input type="radio" name="rate" id="star5" value="5">
        <label for="star5" >5 stars</label>
   
    
        <input type="radio" name="rate" id="star4" value="4">
        <label for="star4" >4 stars</label>
   
        <input type="radio" name="rate" id="star3" value="3">
        <label for="star3" >3 stars</label>
    
        <input type="radio" name="rate" id="star2" value="2">
        <label for="star2" >2 stars</label>    
      
        <input type="radio" name="rate" id="star1" value="1">
        <label for="star1" >1 star</label>
   
    <div class="text-center">
    <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>
</div>
    @endif
@endif


    <h2 class="text-primary">Comments</h2>
    <div  id="commentsContainer">
    @forelse($event->comments as $comment)
        <div class="comment"  data-id="{{ $comment->comment_id}}">
       
        
        <div class="comment-icons-container">
            <p class="comment-author">{{ $comment->author->name }}</p>
            <div>
            @if(auth()->check())
            @if((!$comment->isReported())&& (auth()->user()->user_id !== $comment->author->user_id))
            <i class="fa-solid fa-flag" onclick="showReportPopUp()"></i>
            @endif
            @if(auth()->user()->user_id === $comment->author->user_id)
            <i class="fa-solid fa-pen-to-square" onclick="showEditCommentModal()"></i>
            <i class="fa-solid fa-trash-can" onclick="deleteComment()"></i>
            
            @endif
            @endif
            </div>
        </div>
        
       
        <p class="comment-text" id="commentText">{{ $comment->text }}</p>

        <div class="comment-likes-section">
            <p class="comment-likes">{{ $comment->likes }} 
            @if(auth()->check() && auth()->user()->likes($comment->comment_id))
                
                    <i class="fas fa-thumbs-up fa-solid" id="liked" onclick="unlikeComment()"></i>
                @else
                    <i class="far fa-thumbs-up fa-regular" id="unliked" onclick="likeComment()"></i>
                @endif
            </p>
        </div>

        <form id="editCommentForm"  style="display: none;">

        <textarea id="editedCommentText" class="edit-comment-textbox" rows="3" required>{{ $comment->text }}</textarea>
            <button class="btn btn-primary" onclick="editComment()">Submit</button>
            <button type="button" class="btn btn-danger" onclick="hideEditCommentModal()">Cancel</button>
        </form>

        
      

        </div>
       
 

    @empty
        <p class="text-center">No comments yet.</p>
    @endforelse
    </div>
    @if(auth()->check())
<form id="newCommentForm" action="{{ route('submitComment') }}" method="post">
    @csrf
    <div class="comment new-comment">
        <textarea name="newCommentText" id="newCommentText" class="new-comment-textbox" rows="3" placeholder="Write a new comment"></textarea>
    </div>
    <input id="newCommentEventID" type="hidden" name="event_id"  value="{{$event->event_id}}">
    <button onclick="addNewComment()" class="btn btn-primary" id="submit-comment-button">Submit Comment</button>
</form>
@endif

<div class="pop-up-report">
    <div class="report-section">
        <h3>Why are you reporting?</h3>
        
        
        <form action="{{ route('submitReport') }}" method="post">
            @csrf
            <input type="hidden" name="comment_id" id="reportCommentId" value="0">
            <textarea id="reportReason" class="report-textbox" name="reportReason" rows="4" placeholder="Enter your reason here"></textarea>
            <button type="submit" class="btn btn-primary">Submit Report</button>
        </form>
    </div>
    </div>

   


    




</section>

<section id="ticket-types" class="event-section">
    <h2>Ticket <span>Types</span></h2>
    <form method="POST" action="{{ url('/payment/'.$event->event_id) }}">
        @csrf
        @guest
        <div id="checkout-section" class="auth-form" style="display: none;">
            <div class="text-center"><label for="name">Name</label></div>
            <div class="my-input-group">
                <div class="icon-input">
                    <i class="fas fa-user"></i>
                    <input id="name" type="text" placeholder="Type your name" name="name" value="{{ old('name') }}"
                        required autofocus>
                </div>
                @if ($errors->has('name'))
                <span class="error">
                    {{ $errors->first('name') }}
                </span>
                @endif
            </div>

            <div class="text-center"><label for="email">E-mail</label></div>
            <div class="my-input-group">
                <div class="icon-input">
                    <i class="fas fa-envelope"></i>
                    <input id="email" type="email" placeholder="Type your email" name="email" value="{{ old('email') }}"
                        required>
                </div>
                @if ($errors->has('email'))
                <span class="error">
                    {{ $errors->first('email') }}
                </span>
                @endif
            </div>

            <div class="text-center"><label for="phone">Phone Number</label></div>
            <div class="my-input-group">
                <div class="icon-input">
                    <i class="fas fa-phone"></i>
                    <input id="phone" type="tel" placeholder="Type your phone number" name="phone_number"
                        value="{{ old('phone_number') }}" required pattern="[0-9]{9}">
                </div>
                @if ($errors->has('phone_number'))
                <span class="error">
                    {{ $errors->first('phone_number') }}
                </span>
                @endif
            </div>
        </div>
        @endguest
        <br>
        <div id="ticket-types-container">
            @foreach ($event->ticketTypes as $ticketType)
            <article class="ticket-type" id="ticket-type-{{$ticketType->ticket_type_id}}"
                data-max="{{ min($ticketType->person_buying_limit, $ticketType->stock) }}">
                <h3>{{ $ticketType->name }}</h3>
                <p id="stock_display_{{ $ticketType->ticket_type_id }}">Stock: {{ $ticketType->stock }}</p>
                <p id="event_description_{{ $ticketType->ticket_type_id }}">Description: {{ $ticketType->description }}
                </p>
                <p id="ticket_price_{{ $ticketType->ticket_type_id }}">Price: {{ $ticketType->price }} €</p>
                @if (auth()->user() && auth()->user()->user_id == $event->creator_id)
                @csrf
                <p>New Stock:
                    <input type="number" id="new_stock_{{ $ticketType->ticket_type_id }}" name="new_stock"
                        value="{{ $ticketType->stock }}" required>
                </p>
                <button class="button-update-stock" onclick="updateStock({{ $ticketType->ticket_type_id }})"
                    form="purchaseForm">Update Stock</button>
                @endif
                @if ($ticketType->stock > 0)

                <label class="quant" id="label{{$ticketType->ticket_type_id}}"
                    for="quantity_{{ $ticketType->ticket_type_id }}">Quantity:</label>
                <input class="quant" id="input{{$ticketType->ticket_type_id}}" type="number"
                    id="quantity_{{ $ticketType->ticket_type_id }}" name="quantity[{{ $ticketType->ticket_type_id }}]"
                    min="0" max="{{ min($ticketType->person_buying_limit, $ticketType->stock) }}">

                @endif
            </article>
            @endforeach
        </div>
        <br>
        @auth
        <button type="submit" class="btn btn-success event-button" id="buy-button">
            <i class="fa-solid fa-credit-card"></i> Buy Tickets
        </button>
        @endauth
        @guest
        <button type="button" class="btn btn-success event-button" id="show-form" onclick="toggleCheckoutSection()">
            <i class="fa-solid fa-credit-card"></i> Buy Tickets
        </button>
        <button type="submit" class="btn btn-success event-button" id="buy-button" style="display: none;">
            <i class="fa-solid fa-credit-card"></i> Buy Tickets
        </button>
        @endguest
    </form>
</section>






@if(auth()->user() && auth()->user()->is_admin)
<button class="btn btn-success {{ $event->private ? 'active' : '' }}" id="activate-button"
    data-id="{{ $event->event_id }}">{{ $event->private ? 'Activate Event' : 'Deactivate Event' }}</button>
@endif

    <!-- Edit Event form (displayed only for the event creator) -->
    @can('updateEvent', $event)
        <section id="edit-event" class="event-section">
            <h2>Edit <span>Event</span></h2>
            <article>
                <form method="POST" action="/file/upload" enctype="multipart/form-data">
                    @csrf
                    <input name="file" type="file" required>
                    <input name="id" type="number" value="{{ $event->event_id }}" hidden>
                    <input name="type" type="text" value="event_image" hidden>
                    <button type="submit">Submit</button>
                </form>

                @csrf
                <label for="edit_name">Event Name:</label>
                <input type="text" id="edit_name" name="edit_name" value="{{ $event->name }}" required>

        <label for="edit_description">Event Description:</label>
        <textarea id="edit_description" name="edit_description">{{ $event->description }}</textarea>

        <label for="edit_location">Event Location:</label>
        <input type="text" id="edit_location" name="edit_location" value="{{ $event->location }}" required>

        <label for="edit_start_timestamp">Start Timestamp:</label>
        <input type="datetime-local" id="edit_start_timestamp" name="edit_start_timestamp"
            value="{{ $event->start_timestamp }}" required>

        <label for="edit_end_timestamp">End Timestamp:</label>
        <input type="datetime-local" id="edit_end_timestamp" name="edit_end_timestamp"
            value="{{ $event->end_timestamp }}" required>
        @error('edit_end_timestamp')
        <span class="text-danger">{{ $message }}</span>
        @enderror
        <button class="button-update-event" onclick="updateEvent({{ $event->event_id }})">Update Event</button>
    </article>
</section>

<section id="create-ticket-type" class="event-section">
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
        @enderror <!-- You might want to add more fields based on your requirements -->

        <button type="button" class="btn btn-primary" onclick="createTicketType({{ $event->event_id }})">Create
            TicketType</button>
    </article>
</section>

<section class="event-section" id="event-info">
    <h2>Histórico de Compras</h2>
    @php $totalSoldTickets = 0; $purchaseNumber = 1; @endphp
    @foreach ($soldTickets->groupBy('order_id') as $orderTickets)
    @php
    $totalSoldTickets += $orderTickets->count();
    @endphp
    @endforeach
    <strong>Total de Bilhetes Vendidos:</strong> {{ $totalSoldTickets }}

    @foreach ($soldTickets->groupBy('order_id') as $orderTickets)
    @php
    $order = $orderTickets->first()->order;
    $buyer = $order->buyer;
    @endphp
    <div class="card text-white bg-secondary mb-3" style="max-width: 20rem;">
        <div class="card-header"><strong>Compra #{{ $purchaseNumber }}</strong></div>
        <div class="card-body compra-info">
            <div class="compra-data"><strong>Data:</strong> {{ $order->timestamp }}</div>
            <div class="compra-quanti"><strong>Total:</strong> {{ $orderTickets->count() }}</div>
            <div class="compra-tipos"><strong>Tipos:</strong>
                <ul>
                    @foreach ($orderTickets->groupBy('ticket_type_id') as $ticketType => $typeTickets)
                    @php
                    $ticketTypeName = $typeTickets->first()->ticketType->name;
                    $quantity = $typeTickets->count();
                    @endphp
                    <li class="compra-tipo-nome"> {{ $ticketTypeName }} {{ $quantity }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @php $purchaseNumber++; @endphp
    @endforeach

    <div id="event-charts" data-id="{{ $event->event_id }}">
        <div>
            <canvas id="dif_tickets_chart"></canvas>
        </div>

        <div>
            <canvas id="all_tickets_chart"></canvas>
        </div>

        <div>
            <canvas id="myPieChart"></canvas>
        </div>

        <div class="perc_sold_tickets_charts" id="">
            @foreach ($event->ticketTypes as $ticketType)
            <canvas id="{{ $ticketType->ticket_type_id }}"></canvas>
            @endforeach
        </div>

    </div>
</section>

<p>Faturação: {{ $event->calculateRevenue() }}€</p>


@endcan

@endsection