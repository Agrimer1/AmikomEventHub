<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    function index(Request $request)
    {
        $query = \App\Models\Event::with('category');

        if ($request->has('category') && $request->category != '') {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $events = $query->get();
        $categories = \App\Models\Category::all();
        $partners = \App\Models\Partner::all();

        return view('welcome', compact('events', 'categories', 'partners'));
    }
}
