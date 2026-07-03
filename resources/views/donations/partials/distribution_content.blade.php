<h3 class="font-bold text-xl text-gray-800 mb-2">{{ $campaign->title }}</h3>
<p class="text-xs text-gray-500 mb-4">Laporan Transparansi Penggunaan Dana</p>

<div class="bg-gray-50 p-4 rounded-xl mb-4 grid grid-cols-3 gap-2 text-center text-xs">
    <div>
        <span class="text-gray-500 block mb-1">Terkumpul</span>
        <span class="font-semibold text-gray-800">Rp{{ number_format($campaign->total_collected, 0, ',', '.') }}</span>
    </div>
    <div>
        <span class="text-gray-500 block mb-1">Disalurkan</span>
        <span class="font-semibold text-amber-600">Rp{{ number_format($campaign->total_distributed, 0, ',', '.') }}</span>
    </div>
    <div>
        <span class="text-gray-500 block mb-1">Sisa Saldo</span>
        <span class="font-bold text-emerald-600">Rp{{ number_format($campaign->balance, 0, ',', '.') }}</span>
    </div>
</div>

<h4 class="font-semibold text-sm text-gray-700 mb-2">Riwayat Penyaluran:</h4>
<div class="space-y-3 max-h-60 overflow-y-auto pr-1">
    @if($campaign->distributions->isEmpty())
        <p class="text-gray-400 text-sm text-center py-4">Belum ada penyaluran dana untuk campaign ini.</p>
    @else
        @foreach($campaign->distributions as $dist)
            <div class="border-b border-gray-100 pb-2 text-left">
                <div class="flex justify-between text-sm">
                    <span class="font-semibold text-amber-600">Rp{{ number_format($dist->amount_distributed, 0, ',', '.') }}</span>
                    <span class="text-gray-400 text-xs">{{ $dist->created_at->format('d M Y') }}</span>
                </div>
                <p class="text-xs text-gray-700 font-medium mt-1">Penerima: {{ $dist->beneficiary->name }}</p>
                <p class="text-xs text-gray-500 italic">{{ $dist->notes ?? '-' }}</p>
            </div>
        @endforeach
    @endif
</div>