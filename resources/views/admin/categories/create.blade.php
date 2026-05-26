@extends('layouts.admin')

@section('page_title', 'Tambah Kategori')
@section('page_subtitle', 'Buat kategori baru untuk mengelompokkan event.')

@section('content')
    <div class="max-w-2xl bg-white rounded-[2rem] shadow-sm border p-8">
        <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Nama Kategori</label>
                <input type="text" name="name" value="{{ old('name') }}" 
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition outline-none"
                    placeholder="Contoh: Seminar IT">
                @error('name')
                    <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4 pt-4">
                <button type="submit" class="px-8 py-3 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition">
                    Simpan Kategori
                </button>
                <a href="{{ route('admin.categories.index') }}" class="px-8 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-200 transition text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
