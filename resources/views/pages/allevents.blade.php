<!DOCTYPE html>
<html lang="en">
<head>
    <title>Ticketlane</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/navbar.css') }}">
    <link rel="icon" type="image/png" href="../images/logo1.png">
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js" defer></script>
</head>
<body>
    <!-- Header with buttons and logo -->
    <header>
        <nav class="container">
            <a href="my_events.html"><img class="logo" src="../images/logo.png" alt="logo"></a>

            <ul class="nav-links">
                <li class="home active"> <a href="../user/my_events.html">Home</a></li>
                <li class="f-events"> <a href="../user/events.html">Events</a></li>
                <li class="myTickets"> <a href="../user/my_tickets.html">My tickets</a></li>
                <li class="FAQ"><a href="../common/faq.html">FAQ</a></li>
                <li class="contact"><a href="../common/contact.html">Contact us</a></li>
            </ul>

            <div class="buttons">
                <a href="#"><button class="create-event-btn">Create event</button></a>
                <a href="../user/profile.html"><img class="profile-pic" src="../images/dani.png" alt="Profile Pic"></a>
            </div>
        </nav>
    </header>
    <main>
        <div class="title">Powerful Termand Lineesy Tools</div>

        <div class="grid-cards">
            @foreach($events as $event)
                <a class="my-event-card" href="{{ route('event.show', $event->event_id) }}">
                    <img src="{{ $event->image_url }}" alt="Event Image">
                    <div class="event-details">
                        <div class="event-phrase">{{ $event->name }}</div>
                        <div class="event-date">{{ $event->start_timestamp->format('F d, Y') }}</div>
                    </div>
                </a>
            @endforeach
        </div>
    </main> 
    <footer>
        <div class="footer-container">
            <div class="footer-col">
                <img src="../images/logo.png" alt="ticketlane">
                <p class="project-desc">Elevating Experiences, One Event at
                    a Time. Discover the world of unforgettable moments with
                    Ticketlane.</p>
                <h6 class="rights"> ©2023ticketlane. All rights reserved.
                </h6>
            </div>
            <div class="footer-col">
                <h5>Get in touch</h5>
                <ul class="get-in-touch">
                    <li>
                        <div class="footer-icon-wrapper">
                            <ion-icon name="call"></ion-icon>
                        </div>
                        <a href="#">support@ticketlane.com</a>
                    </li>
                    <li>
                        <div class="footer-icon-wrapper">
                            <ion-icon name="newspaper"></ion-icon>
                        </div>
                        <a href="#">media@ticketlane.com</a>
                    </li>
                    <li>
                        <div class="footer-icon-wrapper">
                            <ion-icon name="people"></ion-icon>
                        </div>
                        <a href="#">partnership@ticketlane.com</a>
                    </li>
                </ul>
            </div>
            <div class="footer-col">
                <h5>Quick links</h5>
                <ul class="quick-links">
                    <li> <ion-icon name="chevron-forward-outline"></ion-icon>
                        <a href="../home.html">Events</a> </li>
                    <li> <ion-icon name="chevron-forward-outline"></ion-icon>
                        <a href="../common/faq.html">FAQ</a> </li>
                    <li> <ion-icon name="chevron-forward-outline"></ion-icon>
                        <a href="../common/contact.html">Contact us</a>
                    </li>
                    <li> <ion-icon name="chevron-forward-outline"></ion-icon>
                        <a href="#">Terms & Conditions</a> </li>
                </ul>
            </div>
            <div class="footer-col follow">
                <h5>Follow us</h5>
                <p> Stay connected and follow our journey on social media
                    for the latest updates</p>
                <ul class="social-media">
                    <li> <a href="#"> <ion-icon name="logo-facebook"></ion-icon>
                        </a> </li>
                    <li> <a href="#"> <ion-icon name="logo-instagram"></ion-icon>
                        </a> </li>
                    <li> <a href="#"> <ion-icon name="logo-twitter"></ion-icon>
                        </a> </li>
                    <li> <a href="#"> <ion-icon name="logo-linkedin"></ion-icon>
                        </a> </li>
                </ul>
            </div>
        </div>
    </footer>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
