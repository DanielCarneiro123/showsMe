<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FAQ;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(): View
    {
        $faqs = FAQ::all();

       
        return view('pages.faq', compact('faqs'));
    }
}
