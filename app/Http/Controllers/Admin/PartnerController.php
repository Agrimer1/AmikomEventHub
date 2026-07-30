<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
    public function __construct(protected CloudinaryService $cloudinary)
    {
        //
    }

    public function index(Request $request)
    {
        $query = Partner::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $partners = $query->latest()->paginate(10);
        return view('admin.partners.index', compact('partners'));
    }

    public function create()
    {
        return view('admin.partners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:partners,name',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $this->cloudinary->upload($request->file('logo'), 'partners');
        }

        Partner::create([
            'name' => $request->name,
            'logo_url' => $logoPath,
        ]);

        return redirect()->route('admin.partners.index')->with('success', 'Partner berhasil ditambahkan');
    }

    public function edit(Partner $partner)
    {
        return view('admin.partners.edit', compact('partner'));
    }

    public function update(Request $request, Partner $partner)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:partners,name,' . $partner->id,
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $logoPath = $partner->logo_url;
        if ($request->hasFile('logo')) {
            if ($partner->logo_url && !str_starts_with($partner->logo_url, 'http') && Storage::disk('public')->exists($partner->logo_url)) {
                Storage::disk('public')->delete($partner->logo_url);
            }
            $logoPath = $this->cloudinary->upload($request->file('logo'), 'partners');
        }

        $partner->update([
            'name' => $request->name,
            'logo_url' => $logoPath,
        ]);

        return redirect()->route('admin.partners.index')->with('success', 'Partner berhasil diperbarui');
    }

    public function destroy(Partner $partner)
    {
        if ($partner->logo_url && !str_starts_with($partner->logo_url, 'http') && Storage::disk('public')->exists($partner->logo_url)) {
            Storage::disk('public')->delete($partner->logo_url);
        }

        $partner->delete();

        return redirect()->route('admin.partners.index')->with('success', 'Partner berhasil dihapus');
    }
}
