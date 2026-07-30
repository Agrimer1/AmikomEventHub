<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use App\Http\Requests\Admin\StorePromoCodeRequest;
use App\Http\Requests\Admin\UpdatePromoCodeRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PromoCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $promoCodes = PromoCode::latest()->paginate(10);
        return view('admin.promo_codes.index', compact('promoCodes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.promo_codes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePromoCodeRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['code'] = strtoupper(trim($validated['code']));
        $validated['min_transaction'] = $validated['min_transaction'] ?? 0;

        PromoCode::create($validated);

        return redirect()
            ->route('admin.promo-codes.index')
            ->with('success', 'Kode Promo berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PromoCode $promoCode): View
    {
        return view('admin.promo_codes.edit', compact('promoCode'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePromoCodeRequest $request, PromoCode $promoCode): RedirectResponse
    {
        $validated = $request->validated();
        $validated['code'] = strtoupper(trim($validated['code']));
        $validated['min_transaction'] = $validated['min_transaction'] ?? 0;

        $promoCode->update($validated);

        return redirect()
            ->route('admin.promo-codes.index')
            ->with('success', 'Kode Promo berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PromoCode $promoCode): RedirectResponse
    {
        $promoCode->delete();

        return redirect()
            ->route('admin.promo-codes.index')
            ->with('success', 'Kode Promo berhasil dihapus!');
    }
}
