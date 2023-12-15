@extends('layouts.app')

@section('content')


<div class="container-fluid">
    <div class="d-flex justify-content-center text-center">
        <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
            <input type="radio" class="btn-check" name="sectionToggle" id="eventOverview" autocomplete="off" checked
                data-section-id="event-overview">
            <label class="btn btn-outline-primary" for="eventOverview">Overview</label>
            <input type="radio" class="btn-check" name="sectionToggle" id="ticketTypes" autocomplete="off" checked
                data-section-id="ticket-types">
            <label class="btn btn-outline-primary" for="ticketTypes">Ticket Types</label>
            <input type="radio" class="btn-check" name="sectionToggle" id="eventComments" autocomplete="off"
                data-section-id="event-comments">
            <label class="btn btn-outline-primary" for="eventComments">Event Comments</label>
            @can('updateEvent', $event)
            <input type="radio" class="btn-check" name="sectionToggle" id="editEvent" autocomplete="off"
                data-section-id="edit-event">
            <label class="btn btn-outline-primary" for="editEvent">Edit Event</label>

            <input type="radio" class="btn-check" name="sectionToggle" id="createTicketType" autocomplete="off"
                data-section-id="create-ticket-type">
            <label class="btn btn-outline-primary" for="createTicketType">Create Ticket Type</label>
            <input type="radio" class="btn-check" name="sectionToggle" id="eventInfo" autocomplete="off"
                data-section-id="event-info">
            <label class="btn btn-outline-primary" for="eventInfo">Event Info</label>
            @endcan
        </div>
    </div>
</div>

<div class="mt-4"></div>

<section id="event-overview" class="event-section">
    @if(auth()->user() && auth()->user()->is_admin)
    <button class="event-button {{ $event->private ? 'active' : '' }}" id="activate-button"
        data-id="{{ $event->event_id }}">{{ $event->private ? 'Activate Event' : 'Deactivate Event' }}</button>
    @endif
    <!--@if(auth()->user() && auth()->user()->is_admin)
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
    @endif-->
    <section>
        <h1 id="name">{{ $event->name }}</h1>
        <p id="description">{{ $event->description }}</p>
        <section class="ratings-event">
            <p id="average-rating"> Rating: {{ number_format($event->averageRating, 1) }} <span
                    class="star-icon">★</span></p>

        </section>
        <p id="location">Location: {{ $event->location }}</p>
    </section>

    <div class="slideshow-container">
        @php $index = 1; @endphp

        @foreach ($event->images as $image)
        <div class="mySlides faded">
            <div class="numbertext">
                {{ $index }} / {{ count($event->images) }}
            </div>
            <img src="{{ asset('event_image/' . $image->image_path) }}" alt="Event Image">
            <span class="delete-icon" data-event-image-id="{{ $image->event_image_id }}" onclick="deleteImage(this)">
                    &#10006;
            </span>
        </div>
        @php $index++; @endphp
        @endforeach

        <!-- Next and previous buttons -->
        <a class="prev-img" onclick="plusSlides(-1)">&#10094;</a>
        <a class="next-img" onclick="plusSlides(1)">&#10095;</a>
    </div>
</section>

<section id="event-comments" class="event-section">
    @if(auth()->user())
    @if($userRating = $event->userRating())
    <p id="yourRatingP" class="text-center">
        Your Rating: {{ $userRating->rating }}
        <span class="star-icon">★</span>
        <button class="btn btn-primary" onclick="showEditRatingForm()">Edit</button>
    </p>
    <div class="centered-form">
        <form id="editRatingForm" class="rate" method="POST"
            action="{{ route('editRating', ['eventId' => $event->event_id]) }}" style="display: none;">
            @csrf


            <input type="radio" name="rate" id="star5" value="5" {{ $userRating->rating == 5 ? 'checked' : '' }}>
            <label for="star5">5 stars</label>


            <input type="radio" name="rate" id="star4" value="4" {{ $userRating->rating == 4 ? 'checked' : '' }}>
            <label for="star4">4 stars</label>

            <input type="radio" name="rate" id="star3" value="3" {{ $userRating->rating == 3 ? 'checked' : '' }}>
            <label for="star3">3 stars</label>

            <input type="radio" name="rate" id="star2" value="2" {{ $userRating->rating == 2 ? 'checked' : '' }}>
            <label for="star2">2 stars</label>

            <input type="radio" name="rate" id="star1" value="1" {{ $userRating->rating == 1 ? 'checked' : '' }}>
            <label for="star1">1 star</label>

            <br>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
    @else
    <p class="text-center rate"> Give us your Rating: </p>
    <div class="centered-form">
        <form id="ratingForm" class="rate" method="POST"
            action="{{ route('submitRating', ['eventId' => $event->event_id]) }}">
            @csrf

            <input type="radio" name="rate" id="star5" value="5">
            <label for="star5">5 stars</label>


            <input type="radio" name="rate" id="star4" value="4">
            <label for="star4">4 stars</label>

            <input type="radio" name="rate" id="star3" value="3">
            <label for="star3">3 stars</label>

            <input type="radio" name="rate" id="star2" value="2">
            <label for="star2">2 stars</label>

            <input type="radio" name="rate" id="star1" value="1">
            <label for="star1">1 star</label>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
    @endif
    @endif


    <h2 class="text-primary text-center">Public Comments</h2>
    <div id="public-comments-section" class="commentsContainer">
        @foreach($event->comments->where('private', false) as $comment)
        <div class="comment" data-id="{{ $comment->comment_id }}">
            <div class="comment-icons-container">
                <p class="comment-author">{{ $comment->author->name }}</p>
                <div>
                    <i class="toggle-eye fa-solid fa-eye show-icon" id="show_{{ $comment->comment_id }}"
                        onclick="toggleCommentVisibility('{{ $comment->comment_id }}', 'show', 'private')"></i>
                    <i class="toggle-eye fa-solid fa-eye-slash hidden-icon" id="hidden_{{ $comment->comment_id }}"
                        onclick="toggleCommentVisibility('{{ $comment->comment_id }}', 'hide', 'public')"
                        style="display: none;"></i>
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

            <form id="editCommentForm" style="display: none;">



                <textarea id="editedCommentText" class="edit-comment-textbox" rows="3"
                    required>{{ $comment->text }}</textarea>
                <button class="btn btn-primary" onclick="editComment()">Submit</button>
                <button type="button" class="btn btn-danger" onclick="hideEditCommentModal()">Cancel</button>
            </form>

            <div class="comment-likes-section">

                @if(auth()->check() && auth()->user()->likes($comment->comment_id))
                <i class="fas fa-thumbs-up fa-solid" id="liked" onclick="unlikeComment()"></i>
                @else
                <i class="far fa-thumbs-up fa-regular" id="unliked" onclick="likeComment()"></i>
                @endif
                <p class="comment-likes">{{ $comment->likes }}</p>

            </div>

        </div>
        @endforeach
    </div>

    @if(auth()->user() && auth()->user()->is_admin)
    <h2 class="text-primary mt-4 text-center">Private Comments (visible to admins only)</h2>
    <div id="private-comments-section" class="commentsContainer">

        @foreach($event->comments->where('private', true) as $comment)
        <div class="comment" data-id="{{ $comment->comment_id }}">
            <div class="comment-icons-container">
                <p class="comment-author">{{ $comment->author->name }}</p>
                <div>
                    <i class="toggle-eye fa-solid fa-eye show-icon" id="show_{{ $comment->comment_id }}"
                        onclick="toggleCommentVisibility('{{ $comment->comment_id }}', 'show', 'private')"></i>
                    <i class="toggle-eye fa-solid fa-eye-slash hidden-icon" id="hidden_{{ $comment->comment_id }}"
                        onclick="toggleCommentVisibility('{{ $comment->comment_id }}', 'hide', 'public')"
                        style="display: none;"></i>
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

            <form id="editCommentForm" style="display: none;">

                <textarea id="editedCommentText" class="edit-comment-textbox" rows="3"
                    required>{{ $comment->text }}</textarea>
                <button class="btn btn-primary" onclick="editComment()">Submit</button>
                <button type="button" class="btn btn-danger" onclick="hideEditCommentModal()">Cancel</button>
            </form>
            <div class="comment-likes-section">

                @if(auth()->check() && auth()->user()->likes($comment->comment_id))
                <i class="fas fa-thumbs-up fa-solid" id="liked" onclick="unlikeComment()"></i>
                @else
                <i class="far fa-thumbs-up fa-regular" id="unliked" onclick="likeComment()"></i>
                @endif
                <p class="comment-likes">{{ $comment->likes }}</p>

            </div>
        </div>
        @endforeach
    </div>
    @endif

    @if(auth()->check())
    <form id="newCommentForm" action="{{ route('submitComment') }}" method="post">
        @csrf
        <div class="comment new-comment">
            <textarea name="newCommentText" id="newCommentText" class="new-comment-textbox" rows="3"
                placeholder="Write a new comment"></textarea>
        </div>
        <input id="newCommentEventID" type="hidden" name="event_id" value="{{$event->event_id}}">
        <button onclick="addNewComment()" class="btn btn-primary" id="submit-comment-button">Submit Comment</button>
    </form>
    @endif

    <div class="pop-up-report">
        <div class="report-section">
            <h3>Why are you reporting?</h3>


            <form action="{{ route('submitReport') }}" method="post">
                @csrf
                <input type="hidden" name="comment_id" id="reportCommentId" value="0">
                <textarea id="reportReason" class="report-textbox" name="reportReason" rows="4"
                    placeholder="Enter your reason here"></textarea>
                <button type="submit" class="btn btn-primary">Submit Report</button>
            </form>
        </div>
    </div>
</section>

<section id="ticket-types" class="event-section">
    <h2 class="text-center">Ticket <span>Types</span></h2>
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
        <div id="ticket-types-container" class="text-center">
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
        <div class="d-flex justify-content-center">
            @auth
            <button type="submit" class="btn btn-success event-button" id="buy-button">
                <i class="fa-solid fa-credit-card"></i> Buy Tickets
            </button>
            @endauth

            @guest
            <button type="button" class="btn btn-success event-button" id="show-form" onclick="toggleCheckoutSection()">
                <i class="fa-solid fa-credit-card"></i> Buy Tickets
            </button>
            @endguest
        </div>

    </form>
</section>









<!-- Edit Event form (displayed only for the event creator) -->
@can('updateEvent', $event)
<section id="edit-event" class="event-section edit-or-create">
    <h2>Edit <span>Event</span></h2>
    <article>
        @csrf

        <form method="POST" action="/file/upload" enctype="multipart/form-data">
            @csrf
            <input name="file" type="file" required>
            <input name="id" type="number" value="{{ $event->event_id }}" hidden>
            <input name="type" type="text" value="event_image" hidden>
            <button type="submit">Submit</button>
        </form>


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

<section id="create-ticket-type" class="event-section edit-or-create">
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

<section class="event-section event-info-content" id="event-info">
    <h2 id="hist">Histórico de Compras</h2>

    <section id="hist-compras">
        @php
        $totalSoldTickets = 0;
        $purchaseNumber = 1;
        @endphp

        @foreach ($soldTickets->groupBy('order_id') as $orderTickets)
        @php
        $order = $orderTickets->first()->order;
        @endphp

        <div class="hist-compras-cards">
            <div class="compra-nr">#{{ $purchaseNumber }}</div>
            <div class="compra-data"> {{ $order->timestamp->format('d/m/Y') }}</div>
            <div class="compra-quanti-valor">
                <p class="bil">Bilhetes</p>
                <p class="bil-total">{{ $orderTickets->count() }}</p>
                <p class="val">Valor Total </p>
                <p class="val-total">{{ $order->totalPurchaseAmount }}€</p>
            </div>
            <div class="div-compra-tipos">
                @foreach ($orderTickets->groupBy('ticket_type_id') as $ticketType => $typeTickets)
                @php
                $ticketTypeName = $typeTickets->first()->ticketType->name;
                $quantity = $typeTickets->count();
                @endphp
                <ul class="compra-tipos">
                    <p class="tipo-nome"> {{ $ticketTypeName }} </p>
                    <p class="quant"> {{ $quantity }} </p>
                </ul>
                @endforeach
            </div>
        </div>

        @php
        $totalSoldTickets += $orderTickets->count();
        $purchaseNumber++;
        @endphp
        @endforeach
    </section>

    <h2 id="title-charts">Stats</h2>

    <section id="event-charts" data-id="{{ $event->event_id }}">
        <div class="div_dif_tickets_chart">
            <canvas id="dif_tickets_chart"></canvas>
        </div>

        <div class="div_all_tickets_chart">
            <canvas id="all_tickets_chart"></canvas>
        </div>

        <div class="div_myPieChart">
            <canvas id="myPieChart"></canvas>
        </div>

        <div class="perc_sold_tickets_charts" id="">
            @foreach ($event->ticketTypes as $ticketType)
            <canvas id="{{ $ticketType->ticket_type_id }}"></canvas>
            @endforeach
        </div>

    </section>

    <div class="faturacao">
        <p id="revenue">Revenue</p>
        <p id="val_revenue"> {{ $event->calculateRevenue() }}€</p>
        <p id="tickets">Tickets</p>
        <p id="total_tickets"> {{ $totalSoldTickets }} </p>
    </div>

</section>


@endcan

@endsection