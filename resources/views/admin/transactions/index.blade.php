@extends('admin.layouts.app')

@section('title', 'Laporan Transaksi')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <!-- Header & Action Buttons -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-slate-800">Laporan Transaksi Donasi</h2>
                <p class="text-slate-500 text-xs sm:text-sm mt-0.5 sm:mt-1">Pantau seluruh aliran dana donasi masuk dari donatur secara real-time.</p>
            </div>
            
            <div class="flex items-center gap-2 sm:gap-2.5">
                <a href="{{ route('admin.transactions.exportExcel') }}" 
                   class="flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 sm:gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-semibold px-3 sm:px-4 py-2 sm:py-2.5 rounded-xl transition shadow-xs">
                    <i class="ti ti-file-spreadsheet text-base"></i> Export Excel
                </a>
                <a href="{{ route('admin.transactions.exportPdf') }}" 
                   class="flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 sm:gap-2 bg-red-600 hover:bg-red-700 text-white text-xs sm:text-sm font-semibold px-3 sm:px-4 py-2 sm:py-2.5 rounded-xl transition shadow-xs">
                    <i class="ti ti-file-type-pdf text-base"></i> Cetak PDF
                </a>
            </div>
        </div>

        <!-- Main Card Wrapper -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden flex flex-col">
            <div class="overflow-x-auto min-w-full">
                <table class="w-full text-left border-collapse min-w-[700px]">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 text-[11px] sm:text-xs font-bold uppercase tracking-wider">
                            <th class="px-4 sm:px-6 py-3.5">ID Order / Waktu</th>
                            <th class="px-4 sm:px-6 py-3.5">Donatur</th>
                            <th class="px-4 sm:px-6 py-3.5">Alokasi Kampanye</th>
                            <th class="px-4 sm:px-6 py-3.5">Nominal</th>
                            <th class="px-4 sm:px-6 py-3.5">Metode</th>
                            <th class="px-4 sm:px-6 py-3.5">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y border-slate-100 text-xs sm:text-sm font-medium text-slate-700">
                        @forelse($transactions as $transaction)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap">
                                    <p class="font-bold text-slate-800 text-xs sm:text-sm">#{{ $transaction->order_id }}</p>
                                    <p class="text-[11px] sm:text-xs text-slate-400 font-normal mt-0.5">
                                        {{ $transaction->created_at->translatedFormat('d M Y, H:i') }} WIB
                                    </p>
                                </td>

                                {{-- KOLOM DONATUR: HANYA HAMBA ALLAH + DOA JIKA ANONIM --}}
                                <td class="px-4 sm:px-6 py-3.5 min-w-[200px]">
                                    <div class="space-y-1">
                                        @if($transaction->is_anonymous)
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-bold text-slate-800 text-xs sm:text-sm">Hamba Allah</span>
                                                <span class="text-[9px] sm:text-[10px] font-bold bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded border border-slate-200 shrink-0">
                                                    Anonim
                                                </span>
                                            </div>
                                        @else
                                            <p class="font-bold text-slate-800 text-xs sm:text-sm truncate max-w-[220px]">
                                                {{ $transaction->user->name ?? 'Donatur' }}
                                            </p>
                                            <p class="text-[11px] sm:text-xs text-slate-400 font-normal truncate max-w-[220px]">
                                                {{ $transaction->user->email ?? '-' }}
                                            </p>
                                        @endif

                                        {{-- Tampilkan Pesan / Doa Kebaikan (Tampil baik Anonim maupun Tidak) --}}
                                        @if($transaction->notes)
                                            <div class="text-[11px] text-slate-600 italic bg-slate-50/80 p-2 rounded-lg border border-slate-200/80 mt-1 max-w-xs leading-relaxed">
                                                <i class="ti ti-quote text-blue-500 mr-0.5"></i> {{ $transaction->notes }}
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-4 sm:px-6 py-3.5 text-slate-600 max-w-[180px] sm:max-w-xs">
                                    <p class="line-clamp-2 leading-snug">
                                        {{ $transaction->campaign->title ?? 'Umum / Global' }}
                                    </p>
                                </td>

                                <td class="px-4 sm:px-6 py-3.5 text-slate-800 font-extrabold text-xs sm:text-base whitespace-nowrap">
                                    Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                </td>

                                <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap">
                                    <span class="bg-slate-100 text-slate-700 px-2 py-1 rounded-md text-[10px] sm:text-xs font-bold uppercase inline-block">
                                        {{ str_replace('_', ' ', $transaction->payment_type ?? 'Gopay/Transfer') }}
                                    </span>
                                </td>

                                <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap">
                                    @if ($transaction->status === 'settlement' || $transaction->status === 'success')
                                        <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 px-2.5 py-1 rounded-full text-[11px] sm:text-xs font-semibold">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Berhasil
                                        </span>
                                    @elseif($transaction->status === 'pending')
                                        <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 px-2.5 py-1 rounded-full text-[11px] sm:text-xs font-semibold">
                                            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span> Menunggu
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 bg-red-50 text-red-700 px-2.5 py-1 rounded-full text-[11px] sm:text-xs font-semibold">
                                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span> Gagal
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 sm:px-6 py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <i class="ti ti-receipt-off text-3xl sm:text-4xl text-slate-300"></i>
                                        <p class="text-sm sm:text-base font-semibold text-slate-500">Belum Ada Transaksi Donasi</p>
                                        <p class="text-xs sm:text-sm font-normal text-slate-400 max-w-sm">Riwayat donasi akan tercatat otomatis di sini setelah sistem API Midtrans terhubung.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Wrapper -->
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-slate-100 bg-slate-50/50 pagination-wrapper">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <style>
        /* Mengamankan ukuran ikon navigasi arah panah bawaan Laravel */
        .pagination-wrapper svg {
            display: inline-block;
            width: 1.25rem;
            height: 1.25rem;
        }

        /* Mengatur jarak tumpuk informasi mobile view agar rapi */
        .pagination-wrapper nav div:first-child {
            margin-bottom: 0.5rem;
        }

        @media (min-width: 640px) {
            .pagination-wrapper nav div:first-child {
                margin-bottom: 0;
            }
        }
    </style>
@endpush