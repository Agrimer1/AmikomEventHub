<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function index(Request $request)
    {
        $query = Jabatan::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $jabatans = $query->latest()->paginate(10);
        return view('admin.jabatan.index', compact('jabatans'));
    }

    public function create()
    {
        return view('admin.jabatan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:jabatan,name',
        ], [
            'name.required' => 'Nama jabatan wajib diisi.',
            'name.max' => 'Nama jabatan maksimal 100 karakter.',
            'name.unique' => 'Nama jabatan sudah digunakan.',
        ]);

        Jabatan::create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.jabatan.index')->with('success', 'Jabatan berhasil ditambahkan');
    }

    public function edit(Jabatan $jabatan)
    {
        return view('admin.jabatan.edit', compact('jabatan'));
    }

    public function update(Request $request, Jabatan $jabatan)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:jabatan,name,' . $jabatan->id,
        ], [
            'name.required' => 'Nama jabatan wajib diisi.',
            'name.max' => 'Nama jabatan maksimal 100 karakter.',
            'name.unique' => 'Nama jabatan sudah digunakan.',
        ]);

        $jabatan->update([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.jabatan.index')->with('success', 'Jabatan berhasil diperbarui');
    }

    public function destroy(Jabatan $jabatan)
    {
        $jabatan->delete();
        return redirect()->route('admin.jabatan.index')->with('success', 'Jabatan berhasil dihapus');
    }
}
