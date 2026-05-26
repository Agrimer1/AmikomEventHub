<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;

class EventController extends Controller
{
    // ======================
    // USER AREA
    // ======================

    // Detail event
    public function show($id)
    {
        $event = Event::findOrFail($id);
        return view('event-detail', compact('event'));
    }

    // Halaman checkout
    public function checkout()
    {
        return view('checkout');
    }

    // Halaman tiket
    public function ticket()
    {
        return view('ticket');
    }
}