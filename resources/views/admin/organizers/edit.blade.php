@extends('layouts.admin')

@section('page_title', 'Edit Organizer')
@section('page_subtitle', 'Perbarui profil dan logo organizer')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-2xl p-8 border border-slate-100 shadow-sm">
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-xl font-bold">Edit Profil Organizer</h2>
        <a href="{{ route('admin.organizers.index') }}" class="text-slate-500 hover:text-slate-700 font-bold text-sm">
            &larr; Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-sm font-medium">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.organizers.update', $organizer->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Nama Organizer / Komunitas</label>
            <input type="text" name="name" value="{{ old('name', $organizer->name) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 font-bold" required>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Email Login</label>
                <input type="email" name="email" value="{{ old('email', $organizer->email) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 font-medium" required>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">No. WhatsApp / Telepon</label>
                <input type="tel" name="phone" value="{{ old('phone', $organizer->phone) }}" placeholder="08xxxxxxxxxx" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 font-medium">
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Logo Organizer</label>
            @if($organizer->logo_url)
                <div class="mb-3 flex items-center gap-3">
                    <img src="{{ $organizer->logo_url }}" alt="{{ $organizer->name }}" class="w-16 h-16 rounded-xl object-cover border border-slate-200">
                    <span class="text-xs text-slate-400 font-medium">Logo saat ini. Upload file baru jika ingin mengganti.</span>
                </div>
            @endif
            <input type="file" name="logo" accept="image/*" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 font-medium text-sm">
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
