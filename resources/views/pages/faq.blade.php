<!-- resources/views/pages/faq.blade.php -->

@extends('layouts.app')  

@section('content')
    <h1>Frequently Asked Questions (FAQs)</h1>

    <section class="faqs-grid">
        @foreach ($faqs as $faq)
            <div class="faq-dropdown">
                <div class="faq-title">
                    <i class="fa-solid fa-caret-down rotate"></i>
                    <h3 class="faq-question">{{ $faq->question }}</h3>
                </div>
                <div class="faq-answer">
                    <p>{{ $faq->answer }}</p>
                </div>
            </div>
        @endforeach
    </section>
@endsection
