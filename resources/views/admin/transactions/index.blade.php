@extends('layouts.admin')
 @section('title', 'Laporan Transaksi - Admin')
 @section('page_title', 'Laporan Transaksi')
 @section('page_subtitle', 'Pantau arus kas dan penjualan tiket Anda.')

 @section('content')
 <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
     <div class="p-6 bg-slate-50/50 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
         <div>
             <h3 class="text-lg font-black text-slate-800">Daftar Transaksi</h3>
             <p class="text-xs text-slate-500">Menampilkan semua transaksi pemesanan tiket masuk.</p>
         </div>
         <div class="flex items-center gap-3">
             <span class="px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-xl text-xs font-bold">
                 Total: {{ $transactions->total() }} Transaksi
             </span>
         </div>
     </div>

     <div class="overflow-x-auto">
         <table class="w-full text-left border-collapse">
             <thead>
                 <tr class="bg-slate-50/75 border-b border-slate-100 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                     <th class="px-8 py-4">Order ID</th>
                     <th class="px-8 py-4">Detail Pembeli</th>
                     <th class="px-8 py-4">Event</th>
                     <th class="px-8 py-4">Tgl Transaksi</th>
                     <th class="px-8 py-4">Rincian Biaya</th>
                     <th class="px-8 py-4">Status</th>
                 </tr>
             </thead>
             <tbody class="divide-y divide-slate-100">
                 @forelse($transactions as $trx)
                 <tr class="hover:bg-indigo-50/10 transition-all duration-200">
                     <!-- Order ID -->
                     <td class="px-8 py-5">
                         <div class="flex flex-col">
                             <span class="font-mono font-black px-3 py-1 rounded-lg text-sm bg-indigo-50 text-indigo-700 border border-indigo-100 shadow-sm w-fit">
                                 {{ $trx->order_id }}
                             </span>
                         </div>
                     </td>
                     
                     <!-- Detail Pembeli -->
                     <td class="px-8 py-5">
                         <div class="space-y-1">
                             <div class="flex items-center gap-2">
                                 <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                 </svg>
                                 <span class="font-bold text-slate-800 text-sm tracking-tight">{{ $trx->customer_name }}</span>
                             </div>
                             <div class="flex flex-col text-xs text-slate-500 pl-6 space-y-0.5">
                                 <span class="flex items-center gap-1">
                                     <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                     </svg>
                                     {{ $trx->customer_email }}
                                 </span>
                                 <span class="flex items-center gap-1">
                                     <svg class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 00.996.808H10.5a3 3 0 013 3v.172a1 1 0 00.808.996l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-3C9.716 21 3 14.284 3 6V5z"></path>
                                     </svg>
                                     {{ $trx->customer_phone }}
                                 </span>
                             </div>
                         </div>
                     </td>

                     <!-- Event -->
                     <td class="px-8 py-5">
                         <div class="flex items-center gap-3">
                             <img src="{{ $trx->event ? $trx->event->poster_url : asset('assets/concert.png') }}" 
                                  alt="Event Poster" 
                                  class="w-12 h-12 rounded-xl object-cover border border-slate-100 shadow-sm">
                             <div>
                                 <h4 class="font-bold text-slate-800 text-sm line-clamp-1">{{ $trx->event->title ?? '-' }}</h4>
                                 <p class="text-xs text-slate-500 flex items-center gap-1 mt-0.5">
                                     <svg class="w-3 h-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                     </svg>
                                     {{ $trx->event->location ?? '-' }}
                                 </p>
                             </div>
                         </div>
                     </td>

                     <!-- Tgl Transaksi -->
                     <td class="px-8 py-5 text-sm text-slate-500">
                         <div class="flex items-center gap-1.5">
                             <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                             </svg>
                             <span>{{ $trx->created_at->format('d M Y, H:i') }}</span>
                         </div>
                     </td>

                     <!-- Rincian Biaya -->
                     <td class="px-8 py-5">
                         <div class="space-y-1 text-xs w-44">
                             <div class="flex justify-between gap-4 text-slate-500">
                                 <span>Harga Tiket:</span>
                                 <span class="font-bold text-slate-700">Rp {{ number_format($trx->event ? $trx->event->price : ($trx->total_price - 5000), 0, ',', '.') }}</span>
                             </div>
                             <div class="flex justify-between gap-4 text-slate-500">
                                 <span>Biaya Layanan:</span>
                                 <span class="font-bold text-slate-700">Rp 5.000</span>
                             </div>
                             <div class="flex justify-between gap-4 pt-1 border-t border-slate-100 font-black text-indigo-600 text-sm">
                                 <span>Total Bayar:</span>
                                 <span>Rp {{ number_format($trx->total_price, 0, ',', '.') }}</span>
                             </div>
                         </div>
                     </td>

                     <!-- Status -->
                     <td class="px-8 py-5">
                         @if($trx->status === 'settlement' || $trx->status === 'success')
                             <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-bold border border-emerald-200 shadow-sm">
                                 <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                 Success
                             </span>
                         @elseif($trx->status === 'pending')
                             <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 text-amber-700 rounded-full text-xs font-bold border border-amber-200 shadow-sm">
                                 <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                 Pending
                             </span>
                         @else
                             <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-50 text-rose-700 rounded-full text-xs font-bold border border-rose-200 shadow-sm">
                                 <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                 {{ ucfirst($trx->status) }}
                             </span>
                         @endif
                     </td>
                 </tr>
                 @empty
                 <tr>
                     <td colspan="6" class="px-8 py-12 text-center">
                         <div class="flex flex-col items-center justify-center space-y-3">
                             <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center text-slate-400">
                                 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                 </svg>
                             </div>
                             <p class="text-slate-500 font-bold">Belum ada transaksi</p>
                             <p class="text-xs text-slate-400">Transaksi baru akan muncul di sini setelah pelanggan melakukan checkout.</p>
                         </div>
                     </td>
                 </tr>
                 @endforelse
             </tbody>
         </table>
     </div>

     @if($transactions->hasPages())
     <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100">
         {{ $transactions->links() }}
     </div>
     @endif
 </div>
 @endsection