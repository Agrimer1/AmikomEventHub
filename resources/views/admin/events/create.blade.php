@extends('layouts.admin')

@section('title', 'Tambah Event Baru - Admin')

@section('content')
<main class="flex-1 p-10 overflow-y-auto bg-[#f8fafc]">
    <header class="flex justify-between items-start mb-10">
        <div>
            <h1 class="text-4xl font-black tracking-tight text-slate-800">Tambah Event Baru</h1>
            <p class="text-slate-500 font-medium mt-1">Masukkan detail acara baru yang akan diselenggarakan.</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="text-right hidden md:block">
                <p class="font-bold text-slate-800 leading-none">Admin</p>
                <p class="text-xs text-slate-400 mt-1">Penyelenggara Utama</p>
            </div>
            <div class="w-12 h-12 bg-white rounded-2xl shadow-sm border border-slate-100 flex items-center justify-center p-1">
                <img src="https://ui-avatars.com/api/?name=Admin+Super&background=6366f1&color=fff" class="rounded-xl">
            </div>
        </div>
    </header>

    <div class="bg-white p-8 md:p-10 rounded-[2.5rem] border border-slate-100 shadow-sm max-w-4xl">
        <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-sm font-black text-slate-400 mb-3 uppercase tracking-widest">Judul Event</label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="Masukkan nama event..."
                        class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none transition font-medium text-slate-700" required>
                    @error('title') <span class="text-rose-500 text-xs font-bold mt-2 block ml-2">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-black text-slate-400 mb-3 uppercase tracking-widest">Kategori</label>
                    <div class="relative">
                        <select name="category_id" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none transition font-medium text-slate-700 appearance-none" required>
                            <option value="">Pilih Kategori</option>
                            @if(isset($categories))
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    @error('category_id') <span class="text-rose-500 text-xs font-bold mt-2 block ml-2">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-black text-slate-400 mb-3 uppercase tracking-widest">Deskripsi Acara</label>
                <textarea name="description" rows="4" placeholder="Jelaskan detail acara Anda..."
                    class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none transition font-medium text-slate-700">{{ old('description') }}</textarea>
                @error('description') <span class="text-rose-500 text-xs font-bold mt-2 block ml-2">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-sm font-black text-slate-400 mb-3 uppercase tracking-widest">Tanggal & Waktu</label>
                    <input type="datetime-local" name="date" value="{{ old('date') }}"
                        class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none transition font-medium text-slate-700" required>
                    @error('date') <span class="text-rose-500 text-xs font-bold mt-2 block ml-2">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-black text-slate-400 mb-3 uppercase tracking-widest">Lokasi</label>
                    <input type="text" name="location" value="{{ old('location') }}" placeholder="Link Zoom atau Alamat Gedung"
                        class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none transition font-medium text-slate-700" required>
                    @error('location') <span class="text-rose-500 text-xs font-bold mt-2 block ml-2">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-sm font-black text-slate-400 mb-3 uppercase tracking-widest">Harga (Rp)</label>
                    <div class="relative">
                        <span class="absolute left-6 top-1/2 -translate-y-1/2 font-bold text-slate-400">Rp</span>
                        <input type="number" name="price" value="{{ old('price', 0) }}"
                            class="w-full pl-14 pr-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none transition font-medium text-slate-700" required min="0">
                    </div>
                    @error('price') <span class="text-rose-500 text-xs font-bold mt-2 block ml-2">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-black text-slate-400 mb-3 uppercase tracking-widest">Kapasitas (Stok)</label>
                    <input type="number" name="stock" value="{{ old('stock', 1) }}"
                        class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none transition font-medium text-slate-700" required min="1">
                    @error('stock') <span class="text-rose-500 text-xs font-bold mt-2 block ml-2">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-black text-slate-400 mb-3 uppercase tracking-widest">Poster Event</label>
                <div class="relative group">
                    <input type="file" name="poster" id="poster" accept="image/*" class="hidden">
                    <label for="poster" class="flex flex-col items-center justify-center w-full h-40 bg-slate-50 border-2 border-dashed border-slate-200 rounded-3xl cursor-pointer hover:bg-indigo-50/50 hover:border-indigo-200 transition group">
                        <svg class="w-8 h-8 text-slate-300 group-hover:text-indigo-400 mb-2 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-sm font-bold text-slate-400 group-hover:text-indigo-500 transition">Klik untuk upload gambar poster</span>
                    </label>
                </div>
                @error('poster') <span class="text-rose-500 text-xs font-bold mt-2 block ml-2">{{ $message }}</span> @enderror
            </div>

            <div class="pt-8 flex justify-end gap-4 border-t border-slate-50">
                <a href="{{ route('admin.events.index') }}" 
                   class="px-8 py-4 text-slate-400 font-black uppercase text-xs tracking-widest hover:text-slate-600 transition">Batal</a>
                <button type="submit" 
                   class="px-10 py-4 bg-indigo-600 text-white rounded-2xl font-black uppercase text-xs tracking-widest shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition">
                    Simpan Event
                </button>
            </div>
        </form>
    </div>
</main>
@endsection