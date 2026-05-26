@extends('layouts.admin')

@section('page_title', 'Tambah Partner')
@section('page_subtitle', 'Tambahkan partner baru untuk kerjasama event.')

@section('content')
    <div class="max-w-2xl bg-white rounded-[2rem] shadow-sm border p-8">
        <form action="{{ route('admin.partners.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Nama Partner</label>
                <input type="text" name="name" value="{{ old('name') }}" 
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition outline-none"
                    placeholder="Contoh: PT Sumber Makmur">
                @error('name')
                    <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Logo Partner</label>
                <input type="file" name="logo" 
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition outline-none file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <p class="text-xs text-slate-400 mt-2">Maksimum ukuran logo 2MB dengan format JPG, JPEG, PNG, GIF, SVG.</p>
                @error('logo')
                    <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4 pt-4">
                <button type="submit" class="px-8 py-3 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition">
                    Simpan Partner
                </button>
                <a href="{{ route('admin.partners.index') }}" class="px-8 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-200 transition text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
