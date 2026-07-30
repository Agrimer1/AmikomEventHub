<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use App\Models\Organizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::forUser()->with(['category', 'organizer'])->latest()->paginate(10);
        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $organizers = auth()->user()->isSuperAdmin() ? Organizer::all() : collect();

        return view('admin.events.create', compact('categories', 'organizers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'organizer_id' => $user->isSuperAdmin() ? 'nullable|exists:organizers,id' : 'nullable',
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'date'         => 'required|date',
            'location'     => 'required|string|max:255',
            'price'        => 'required|numeric|min:0',
            'stock'        => 'required|numeric|min:1',
            'poster'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($user->isOrganizer()) {
            $data['organizer_id'] = $user->organizer?->id;
        }

        if ($request->hasFile('poster')) {
            $data['poster_path'] = $request->file('poster')->store('posters', 'public');
        }

        Event::create($data);

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event berhasil dibuat!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $user = auth()->user();

        // Pengecekan otorisasi policy
        if ($user->isOrganizer() && $event->organizer_id !== $user->organizer?->id) {
            abort(403, 'Anda tidak memiliki hak untuk mengedit event milik organizer lain.');
        }

        $categories = Category::all();
        $organizers = $user->isSuperAdmin() ? Organizer::all() : collect();

        return view('admin.events.edit', compact('event', 'categories', 'organizers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $user = auth()->user();

        if ($user->isOrganizer() && $event->organizer_id !== $user->organizer?->id) {
            abort(403, 'Anda tidak memiliki hak untuk memperbarui event ini.');
        }

        $data = $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'organizer_id' => $user->isSuperAdmin() ? 'nullable|exists:organizers,id' : 'nullable',
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'date'         => 'required|date',
            'location'     => 'required|string|max:255',
            'price'        => 'required|numeric|min:0',
            'stock'        => 'required|numeric|min:1',
            'poster'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($user->isOrganizer()) {
            $data['organizer_id'] = $user->organizer?->id;
        }

        if ($request->hasFile('poster')) {
            if ($event->poster_path && Storage::disk('public')->exists($event->poster_path)) {
                Storage::disk('public')->delete($event->poster_path);
            }
            $data['poster_path'] = $request->file('poster')->store('posters', 'public');
        }
    
        $event->update($data);
        return redirect()->route('admin.events.index')->with('success', 'Event berhasil diperbarui.');
    }    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $user = auth()->user();

        if ($user->isOrganizer() && $event->organizer_id !== $user->organizer?->id) {
            abort(403, 'Anda tidak memiliki hak untuk menghapus event ini.');
        }

        if ($event->poster_path && Storage::disk('public')->exists($event->poster_path)) {
            Storage::disk('public')->delete($event->poster_path);
        }

        $event->delete();

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event berhasil dihapus.');
    }
}
