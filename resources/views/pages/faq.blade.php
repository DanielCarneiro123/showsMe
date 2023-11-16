<!-- resources/views/pages/faq.blade.php -->

@extends('layouts.app')  

@section('content')
    <h1>Frequently Asked Questions (FAQs)</h1>

    @foreach ($faqs as $faq)
        <div>
            <h3>{{ $faq->question }}</h3>
            <p>{{ $faq->answer }}</p>
        </div>
    @endforeach
@endsection
