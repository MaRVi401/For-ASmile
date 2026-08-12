<div class="space-y-4">
    <!-- Header Modal -->
    <div class="border-b border-slate-100 pb-3">
        <h3 class="font-bold text-slate-800 text-base sm:text-lg leading-tight">{{ $campaign->title }}</h3>
        <p class="text-xs text-slate-500 mt-1">Laporan Transparansi Penyaluran Dana</p>
    </div>

    <!-- Ringkasan Saluran Dana -->
    <div class="grid grid-cols-2 gap-2 bg-blue-50/60 p-3 rounded-xl border border-blue-100/80 text-xs">
        <div>
            <span class="text-slate-400 block text-[10px] uppercase font-bold">Total Disalurkan</span>
            <span class="font-extrabold text-blue-700">Rp {{ number_format($campaign->total_distributed, 0, ',', '.') }}</span>
        </div>
        <div>
            <span class="text-slate-400 block text-[10px] uppercase font-bold">Penerima Bantuan</span>
            <span class="font-extrabold text-slate-700">{{ $campaign->distributions->count() }} Orang/Lembaga</span>
        </div>
    </div>

    @if($campaign->distributions->isEmpty())
        <div class="text-center py-8 text-slate-400 space-y-2">
            <i class="ti ti-folder-off text-4xl text-slate-300 block"></i>
            <p class="text-xs font-semibold text-slate-500">Belum ada riwayat penyaluran dana</p>
            <p class="text-[11px] text-slate-400">Penyaluran dana untuk kampanye ini belum dicatat oleh admin.</p>
        </div>
    @else
        <div class="space-y-3.5 max-h-[55vh] overflow-y-auto pr-1">
            @foreach($campaign->distributions as $dist)
                <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 space-y-3">
                    <!-- Detail Penerima & Nominal -->
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <p class="font-bold text-xs sm:text-sm text-slate-800">
                                {{ $dist->beneficiary->name ?? 'Penerima Manfaat' }}
                            </p>
                            <p class="text-[11px] text-slate-400 flex items-center gap-1 mt-0.5">
                                <i class="ti ti-calendar text-xs"></i>
                                {{ \Carbon\Carbon::parse($dist->distributed_at)->translatedFormat('d M Y, H:i') }} WIB
                            </p>
                        </div>
                        <span class="font-extrabold text-xs sm:text-sm text-blue-600 bg-blue-50 border border-blue-100 px-2.5 py-1 rounded-lg shrink-0">
                            Rp {{ number_format($dist->amount_distributed, 0, ',', '.') }}
                        </span>
                    </div>

                    <!-- Catatan Penyaluran -->
                    @if($dist->notes)
                        <p class="text-xs text-slate-600 bg-white p-2.5 rounded-lg border border-slate-100 leading-relaxed">
                            {{ $dist->notes }}
                        </p>
                    @endif

                    <!-- Gambar Bukti Dokumentasi Penyaluran -->
                    @if(!empty($dist->documentation_image))
                        <div class="pt-1">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 flex items-center gap-1">
                                <i class="ti ti-camera text-xs"></i> Bukti Dokumentasi Fisik:
                            </p>
                            <a href="{{ asset('storage/' . $dist->documentation_image) }}" target="_blank" class="block group relative overflow-hidden rounded-xl border border-slate-200">
                                <img src="{{ asset('storage/' . $dist->documentation_image) }}" 
                                     alt="Bukti Dokumentasi Penyaluran" 
                                     class="w-full h-44 object-cover group-hover:scale-105 transition duration-300 rounded-xl">
                                <span class="absolute inset-0 bg-slate-900/40 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition text-xs font-semibold gap-1.5">
                                    <i class="ti ti-eye"></i> Lihat Foto Ukuran Penuh
                                </span>
                            </a>
                        </div>
                    @else
                        <div class="flex items-center gap-1 text-[11px] text-slate-400 italic pt-0.5">
                            <i class="ti ti-photo-off text-xs"></i> Dokumentasi foto tidak dilampirkan.
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>