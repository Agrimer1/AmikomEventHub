<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use App\Models\Pengurus;
use Illuminate\Http\Request;

class PengurusController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengurus::with('jabatan');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('description', 'LIKE', '%' . $search . '%');
            });
        }

        $penguruses = $query->latest()->paginate(10);
        return view('admin.pengurus.index', compact('penguruses'));
    }

    public function create()
    {
        $jabatans = Jabatan::orderBy('name')->get();
        return view('admin.pengurus.create', compact('jabatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jabatan_id' => 'required|exists:jabatan,id',
            'name' => 'required|string|max:100',
            'description' => 'required|string|max:255',
            'salary' => 'required|numeric|min:0|max:9999999999999.99',
        ], [
            'jabatan_id.required' => 'Jabatan harus dipilih.',
            'jabatan_id.exists' => 'Jabatan tidak valid.',
            'name.required' => 'Nama pengurus wajib diisi.',
            'name.max' => 'Nama pengurus maksimal 100 karakter.',
            'description.required' => 'Deskripsi wajib diisi.',
            'description.max' => 'Deskripsi maksimal 255 karakter.',
            'salary.required' => 'Gaji wajib diisi.',
            'salary.numeric' => 'Gaji harus berupa angka.',
            'salary.min' => 'Gaji tidak boleh kurang dari 0.',
            'salary.max' => 'Gaji melebihi batas nilai maksimum.',
        ]);

        Pengurus::create([
            'jabatan_id' => $request->jabatan_id,
            'name' => $request->name,
            'description' => $request->description,
            'salary' => $request->salary,
        ]);

        return redirect()->route('admin.pengurus.index')->with('success', 'Pengurus berhasil ditambahkan');
    }

    public function edit(Pengurus $penguru)
    {
        $jabatans = Jabatan::orderBy('name')->get();
        return view('admin.pengurus.edit', [
            'pengurus' => $penguru,
            'jabatans' => $jabatans,
        ]);
    }

    public function update(Request $request, Pengurus $penguru)
    {
        $request->validate([
            'jabatan_id' => 'required|exists:jabatan,id',
            'name' => 'required|string|max:100',
            'description' => 'required|string|max:255',
            'salary' => 'required|numeric|min:0|max:9999999999999.99',
        ], [
            'jabatan_id.required' => 'Jabatan harus dipilih.',
            'jabatan_id.exists' => 'Jabatan tidak valid.',
            'name.required' => 'Nama pengurus wajib diisi.',
            'name.max' => 'Nama pengurus maksimal 100 karakter.',
            'description.required' => 'Deskripsi wajib diisi.',
            'description.max' => 'Deskripsi maksimal 255 karakter.',
            'salary.required' => 'Gaji wajib diisi.',
            'salary.numeric' => 'Gaji harus berupa angka.',
            'salary.min' => 'Gaji tidak boleh kurang dari 0.',
            'salary.max' => 'Gaji melebihi batas nilai maksimum.',
        ]);

        $penguru->update([
            'jabatan_id' => $request->jabatan_id,
            'name' => $request->name,
            'description' => $request->description,
            'salary' => $request->salary,
        ]);

        return redirect()->route('admin.pengurus.index')->with('success', 'Pengurus berhasil diperbarui');
    }

    public function destroy(Pengurus $penguru)
    {
        $penguru->delete();
        return redirect()->route('admin.pengurus.index')->with('success', 'Pengurus berhasil dihapus');
    }
}
