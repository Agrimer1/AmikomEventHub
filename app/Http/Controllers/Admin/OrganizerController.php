<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreOrganizerRequest;
use App\Models\Organizer;
use App\Models\User;
use App\Services\CloudinaryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OrganizerController extends Controller
{
    public function __construct(protected CloudinaryService $cloudinary)
    {
        //
    }

    /**
     * Display a listing of organizers (Super Admin Only).
     */
    public function index(): View
    {
        $organizers = Organizer::with(['user', 'events'])->latest()->paginate(10);
        return view('admin.organizers.index', compact('organizers'));
    }

    /**
     * Show form to create new organizer.
     */
    public function create(): View
    {
        return view('admin.organizers.create');
    }

    /**
     * Store new organizer and create associated user account.
     */
    public function store(StoreOrganizerRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // 1. Buat User Account dengan role 'organizer'
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'organizer',
        ]);

        // 2. Upload Logo jika ada
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $this->cloudinary->upload($request->file('logo'), 'organizers');
        }

        // 3. Buat Entitas Organizer
        Organizer::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . Str::random(5),
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'logo_path' => $logoPath,
        ]);

        return redirect()
            ->route('admin.organizers.index')
            ->with('success', 'Akun & Profil Organizer berhasil dibuat!');
    }

    /**
     * Remove organizer and associated user.
     */
    public function destroy(Organizer $organizer): RedirectResponse
    {
        if ($organizer->logo_path && !str_starts_with($organizer->logo_path, 'http') && Storage::disk('public')->exists($organizer->logo_path)) {
            Storage::disk('public')->delete($organizer->logo_path);
        }

        $user = $organizer->user;
        $organizer->delete();

        if ($user) {
            $user->delete();
        }

        return redirect()
            ->route('admin.organizers.index')
            ->with('success', 'Organizer berhasil dihapus!');
    }
}
