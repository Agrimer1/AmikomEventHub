@extends('layouts.admin')

@section('page_title', 'Tambah Pengurus')
@section('page_subtitle', 'Tambahkan anggota pengurus baru.')

@section('content')
    <div class="max-w-2xl bg-white rounded-[2rem] shadow-sm border p-8">
        <form action="{{ route('admin.pengurus.store') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Pilih Jabatan</label>
                <select name="jabatan_id" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition outline-none bg-white">
                    <option value="" disabled selected>-- Pilih Jabatan --</option>
                    @foreach($jabatans as $jabatan)
                        <option value="{{ $jabatan->id }}" {{ old('jabatan_id') == $jabatan->id ? 'selected' : '' }}>
                            {{ $jabatan->name }}
                        </option>
                    @endforeach
                </select>
                @error('jabatan_id')
                    <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Nama Pengurus</label>
                <input type="text" name="name" value="{{ old('name') }}" 
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition outline-none"
                    placeholder="Contoh: John Doe">
                @error('name')
                    <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi Tugas / Peran</label>
                <input type="text" name="description" value="{{ old('description') }}" 
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition outline-none"
                    placeholder="Contoh: Bertanggung jawab atas pengelolaan operasional harian">
                @error('description')
                    <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Gaji (Salary)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 font-medium">
                        Rp
                    </div>
                    <input type="number" name="salary" step="0.01" value="{{ old('salary') }}" 
                        class="w-full pl-12 pr-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition outline-none"
                        placeholder="Contoh: 5000000">
                </div>
                @error('salary')
                    <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4 pt-4">
                <button type="submit" class="px-8 py-3 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition">
                    Simpan Pengurus
                </button>
                <a href="{{ route('admin.pengurus.index') }}" class="px-8 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-200 transition text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
