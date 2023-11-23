<?php

namespace App\Http\Controllers;


use Illuminate\View\View;

class AboutUsController extends Controller
{
    public function index(): View
    {


       
        return view('pages.about_us');
    }
}
