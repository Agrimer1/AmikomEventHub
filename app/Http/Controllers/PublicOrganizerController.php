<?php

namespace App\Http\Controllers;

use App\Models\Organizer;
use App\Models\Review;
use App\Models\Category;
use Illuminate\View\View;

class PublicOrganizerController extends Controller
{
    /**
     * Tampilkan profil publik organizer beserta event dan ulasan dari seluruh event-nya.
     */
    public function show(Organizer $organizer): View
    {
        $categories = Category::all();

        // Load event milik organizer beserta kategori
        $organizer->load(['events.category', 'user']);

        // Event milik organizer
        $events = $organizer->events()->latest()->get();

        // Ambil ID event milik organizer
        $eventIds = $events->pluck('id');

        // Ambil seluruh review dari event-event organizer tersebut
        $reviews = Review::with(['user', 'event'])
            ->whereIn('event_id', $eventIds)
            ->latest()
            ->get();

        // Hitung total review & rata-rata rating
        $totalReviews = $reviews->count();
        $avgRating = $totalReviews > 0 ? round($reviews->avg('rating'), 1) : 0;
        $totalEvents = $events->count();

        return view('organizer-detail', compact(
            'organizer',
            'events',
            'reviews',
            'totalReviews',
            'avgRating',
            'totalEvents',
            'categories'
        ));
    }
}
