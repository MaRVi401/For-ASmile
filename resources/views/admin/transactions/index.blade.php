@extends('admin.layouts.app')

@section('title', 'Laporan Transaksi')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Laporan Transaksi Donasi</h2>
                <p class="text-slate-500 text-sm mt-1">Pantau seluruh aliran dana donasi masuk dari donatur secara real-time.</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('admin.transactions.exportExcel') }}" 
                   class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition shadow-xs cursor-pointer">
                    <i class="ti ti-file-spreadsheet text-base"></i> Export Excel
                </a>
                <a href="{{ route('admin.transactions.exportPdf') }}" 
                   class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition shadow-xs cursor-pointer">
                    <i class="ti ti-file-type-pdf text-base"></i> Cetak PDF
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden flex flex-col">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 text-xs font-bold uppercase tracking-wider">
                            <th class="px-6 py-4">ID Order / Waktu</th>
                            <th class="px-6 py-4">Donatur</th>
                            <th class="px-6 py-4">Alokasi Kampanye</th>
                            <th class="px-6 py-4">Nominal</th>
                            <th class="px-6 py-4">Metode</th>
                            <th class="px-6 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y border-slate-100 text-sm font-medium text-slate-700">
                        @forelse($transactions as $transaction)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-slate-800">#{{ $transaction->order_id }}</p>
                                    <p class="text-xs text-slate-400 font-normal mt-0.5">
                                        {{ $transaction->created_at->translatedFormat('d M Y, H:i') }} WIB
                                    </p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-slate-800">
                                        {{ $transaction->user->name ?? 'Anonim / Hamba Allah' }}
                                    </p>
                                    <p class="text-xs text-slate-400 font-normal mt-0.5">
                                        {{ $transaction->user->email ?? '-' }}
                                    </p>
                                </td>
                                <td class="px-6 py-4 text-slate-600">
                                    {{ $transaction->campaign->title ?? 'Umum / Global' }}
                                </td>
                                <td class="px-6 py-4 text-slate-800 font-bold text-base">
                                    Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="bg-slate-100 text-slate-700 px-2.5 py-1 rounded-md text-xs font-bold uppercase">
                                        {{ str_replace('_', ' ', $transaction->payment_type ?? 'Gopay/Transfer') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($transaction->status === 'settlement' || $transaction->status === 'success')
                                        <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 px-2.5 py-1 rounded-full text-xs font-semibold">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Berhasil
                                        </span>
                                    @elseif($transaction->status === 'pending')
                                        <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 px-2.5 py-1 rounded-full text-xs font-semibold">
                                            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span> Menunggu
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 bg-red-50 text-red-700 px-2.5 py-1 rounded-full text-xs font-semibold">
                                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span> Gagal
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <i class="ti ti-receipt-off text-4xl text-slate-300"></i>
                                        <p class="text-base font-semibold text-slate-500">Belum Ada Transaksi Donasi</p>
                                        <p class="text-sm font-normal text-slate-400">Riwayat donasi akan tercatat otomatis di sini setelah sistem API Midtrans terhubung.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 pagination-wrapper">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <style>
        /* Mengamankan bug ukuran ikon navigasi arah panah bawaan Laravel */
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