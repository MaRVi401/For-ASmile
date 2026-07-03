<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Donasi - For A Smile</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
</head>

<body class="bg-slate-50 text-slate-800 antialiased font-sans">

    <!-- Header / Navbar Minimalis -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('assets/image/fas-logo.png') }}" alt="Logo" class="h-9 w-auto">
                <span class="font-bold text-lg text-slate-900 tracking-wide">For A Smile</span>
            </div>
            <div>
                @auth
                    <a href="{{ route('admin.dashboard') }}"
                        class="text-sm font-semibold text-blue-600 bg-blue-50 px-4 py-2 rounded-xl hover:bg-blue-100 transition">
                        Admin Panel
                    </a>
                @else
                    <span class="text-xs text-slate-400 font-medium bg-slate-100 px-3 py-1.5 rounded-lg">Mode Uji Coba</span>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 py-8 sm:py-12">
        <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-14">
            <h1 class="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight mb-3">Mari Berbagi Senyuman</h1>
            <p class="text-slate-500 text-sm sm:text-base">Pilih salah satu kampanye aktif di bawah ini untuk menguji coba modul pembayaran sistem Midtrans Snap API.</p>
        </div>

        <!-- Grid Cards Kampanye -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            @forelse($campaigns as $campaign)
                <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden flex flex-col hover:shadow-md transition duration-300 group">

                    <!-- Thumbnail Gambar Kampanye -->
                    <div class="h-48 bg-slate-100 flex items-center justify-center relative overflow-hidden">
                        @if($campaign->image_url)
                            <img src="{{ asset('storage/' . $campaign->image_url) }}" alt="{{ $campaign->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-500 ease-out"
                                onerror="this.onerror=null; this.src='data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22100%25%22 height=%22100%25%22><rect width=%22100%25%22 height=%22100%25%22 fill=%22%232563eb%22/><text x=%2250%25%22 y=%2250%25%22 font-family=%22sans-serif%22 font-size=%2224%22 fill=%22white%22 text-anchor=%22middle%22 dy=%22.3em%22>❤️ For A Smile</text></svg>';">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center p-6">
                                <i class="ti ti-heart-handshake text-white text-5xl opacity-80"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Card Body -->
                    <div class="p-5 sm:p-6 flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="font-bold text-slate-900 text-lg leading-snug mb-2 line-clamp-2">
                                {{ $campaign->title }}
                            </h3>
                            <p class="text-slate-500 text-xs sm:text-sm line-clamp-3 mb-4">
                                {{ $campaign->description ?? 'Tidak ada deskripsi tambahan untuk kampanye ini.' }}
                            </p>

                            <!-- Ringkasan Agenda Kerja & Button Detail Program -->
                            <div class="mb-4 bg-slate-50 p-3 rounded-xl border border-slate-100">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Target Agenda Kerja:</span>
                                    <span class="text-[11px] font-semibold bg-blue-50 text-blue-600 px-2 py-0.5 rounded-md">
                                        {{ $campaign->programs->count() }} Program
                                    </span>
                                </div>
                                @if($campaign->programs->isEmpty())
                                    <p class="text-xs text-slate-400 italic">Belum ada rincian sub-program bulan ini.</p>
                                @else
                                    <ul class="space-y-1 max-h-24 overflow-y-auto pr-1 mb-2.5">
                                        @foreach($campaign->programs->take(2) as $program)
                                            <li class="text-xs text-slate-600 flex items-start gap-1.5">
                                                <i class="ti ti-circle-check text-emerald-500 mt-0.5 text-sm"></i>
                                                <!-- PERBAIKAN: Menggunakan $program->program_name -->
                                                <span class="line-clamp-1">{{ $program->program_name }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <button type="button" 
                                            class="btn-lihat-semu-program w-full text-center text-xs text-blue-600 bg-blue-50 hover:bg-blue-100 font-bold py-2 px-3 rounded-lg transition inline-flex items-center justify-center gap-1 cursor-pointer"
                                            data-title="{{ $campaign->title }}"
                                            data-programs="{{ json_encode($campaign->programs) }}">
                                        <i class="ti ti-list-details text-sm"></i> Lihat Detail Program
                                    </button>
                                @endif
                            </div>

                            <!-- Target & Progress Pengumpulan Dana -->
                            <div class="mb-5 space-y-2">
                                <div class="flex justify-between items-end text-xs sm:text-sm">
                                    <div>
                                        <span class="text-slate-400 block text-[11px] uppercase font-bold tracking-wider">Terkumpul</span>
                                        <span class="font-bold text-blue-600">Rp{{ number_format($campaign->total_collected, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-slate-400 block text-[11px] uppercase font-bold tracking-wider">Target</span>
                                        <span class="font-semibold text-slate-700">Rp{{ number_format($campaign->target_amount ?? 0, 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                @php
                                    $target = $campaign->target_amount ?? 0;
                                    $collected = $campaign->total_collected ?? 0;
                                    $percentage = $target > 0 ? min(round(($collected / $target) * 100), 100) : 0;
                                @endphp
                                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden relative">
                                    <div class="bg-blue-600 h-full rounded-full transition-all duration-500" style="width: {{ $percentage }}%;"></div>
                                </div>

                                <div class="flex justify-between items-center text-[11px] text-slate-400 font-medium">
                                    <span>Progress capaian</span>
                                    <span class="text-blue-600 font-bold bg-blue-50 px-1.5 py-0.5 rounded">{{ $percentage }}%</span>
                                </div>
                            </div>
                        </div>

                        <!-- Button Action Utama -->
                        <div class="pt-4 border-t border-slate-100 grid grid-cols-2 gap-2">
                            <a href="{{ route('donation.create', $campaign->id) }}"
                                class="flex items-center justify-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-3 rounded-xl transition cursor-pointer text-sm shadow-xs shadow-blue-600/10">
                                <i class="ti ti-heart text-base"></i> Donasi
                            </a>

                            <button type="button"
                                class="btn-web-distribusi flex items-center justify-center gap-1.5 border border-blue-600 hover:bg-blue-50 text-blue-600 font-semibold py-3 px-3 rounded-xl transition cursor-pointer text-sm"
                                data-id="{{ $campaign->id }}">
                                <i class="ti ti-report-money text-base"></i> Laporan Dana
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white border border-dashed border-slate-300 rounded-2xl p-12 text-center text-slate-500">
                    <i class="ti ti-folder-off text-4xl mb-2 text-slate-300 block"></i> Belum ada data kampanye. Silakan tambahkan lewat admin panel terlebih dahulu.
                </div>
            @endforelse
        </div>
    </main>

    <!-- Modal 1: Laporan Transparansi Distribusi Dana -->
    <div id="webDistributionModal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50 p-4">
        <div class="bg-white w-full max-w-md rounded-2xl p-6 relative">
            <button id="closeWebModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-xl font-bold cursor-pointer">&times;</button>
            <div id="modalDynamicContent">
                <p class="text-center text-gray-500 text-sm">Memuat data...</p>
            </div>
        </div>
    </div>

    <!-- Modal 2: Detail Seluruh Daftar Program Kerja (Layout Card) -->
    <div id="programDetailsModal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50 p-4">
        <div class="bg-white w-full max-w-lg rounded-2xl p-6 relative max-h-[85vh] flex flex-col">
            <button id="closeProgramModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-xl font-bold cursor-pointer">&times;</button>
            
            <div class="flex flex-col h-full overflow-hidden">
                <!-- Header Modal -->
                <h3 id="programModalTitle" class="font-bold text-xl text-gray-800 mb-1 pr-6 leading-tight"></h3>
                
                <!-- Indikator Angka Program Kerja -->
                <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4 mt-1">
                    <p class="text-xs text-gray-500">Daftar Agenda & Sub-Program Kegiatan Kerja</p>
                    <span id="programModalCount" class="text-[11px] font-bold bg-blue-50 text-blue-600 px-2.5 py-1 rounded-lg"></span>
                </div>
                
                <!-- List Item Container Grid Card Program -->
                <div id="programModalList" class="space-y-4 flex-1 overflow-y-auto pr-1 pb-2"></div>
            </div>
        </div>
    </div>

    <!-- AJAX & Modal Control JavaScript Logic -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Kontrol Modal Laporan Dana
            const modal = document.getElementById('webDistributionModal');
            const modalContent = document.getElementById('modalDynamicContent');
            const closeBtn = document.getElementById('closeWebModal');

            document.querySelectorAll('.btn-web-distribusi').forEach(button => {
                button.addEventListener('click', function () {
                    const campaignId = this.getAttribute('data-id');
                    modalContent.innerHTML = '<p class="text-center text-gray-500 text-sm py-4">Memuat data...</p>';
                    modal.classList.remove('hidden');

                    fetch(`/campaigns/${campaignId}/distribution-modal`)
                        .then(response => response.text())
                        .then(htmlOutput => {
                            modalContent.innerHTML = htmlOutput;
                        })
                        .catch(err => {
                            modalContent.innerHTML = '<p class="text-center text-red-500 text-sm py-4">Gagal memuat data.</p>';
                        });
                });
            });

            closeBtn.addEventListener('click', () => modal.classList.add('hidden'));
            modal.addEventListener('click', (e) => {
                if (e.target === modal) modal.classList.add('hidden');
            });

            // Kontrol Modal Detail Program Kerja (Card Layout System)
            const pModal = document.getElementById('programDetailsModal');
            const pTitle = document.getElementById('programModalTitle');
            const pCount = document.getElementById('programModalCount');
            const pList = document.getElementById('programModalList');
            const closePBtn = document.getElementById('closeProgramModal');

            document.querySelectorAll('.btn-lihat-semu-program').forEach(button => {
                button.addEventListener('click', function() {
                    const title = this.getAttribute('data-title');
                    const programs = JSON.parse(this.getAttribute('data-programs'));

                    // Mengisi Judul dan Indikator Jumlah
                    pTitle.innerText = title;
                    pCount.innerText = `${programs.length} Program`;

                    // Render List Agenda dalam bentuk Card Modern
                    pList.innerHTML = '';
                    programs.forEach(prog => {
                        // PERBAIKAN: Memetakan ke properti prog.program_name
                        let namaProgram = prog.program_name || 'Program Kerja';

                        // Cek gambar program jika ada, jika tidak gunakan fallback ikon
                        let imgHtml = prog.image_url 
                            ? `<img src="/storage/${prog.image_url}" alt="${namaProgram}" class="w-full h-full object-cover">`
                            : `<div class="w-full h-full bg-gradient-to-br from-blue-500/10 to-indigo-600/10 flex items-center justify-center">
                                    <i class="ti ti-circle-check text-blue-600 text-xl"></i>
                               </div>`;

                        pList.innerHTML += `
                            <div class="bg-white border border-slate-100 rounded-xl overflow-hidden shadow-xs flex gap-3 p-3 hover:border-slate-200 transition duration-200">
                                <!-- Area Foto Program -->
                                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-slate-50 rounded-lg overflow-hidden flex-shrink-0 border border-slate-100">
                                    ${imgHtml}
                                </div>
                                <!-- Konten Teks Card Program -->
                                <div class="flex-1 min-w-0 flex flex-col justify-center">
                                    <h4 class="font-bold text-sm sm:text-base text-slate-800 leading-tight truncate">${namaProgram}</h4>
                                    <p class="text-xs text-slate-500 mt-1.5 leading-relaxed line-clamp-2">${prog.description ?? 'Tidak ada rincian deskripsi agenda kerja.'}</p>
                                </div>
                            </div>
                        `;
                    });

                    pModal.classList.remove('hidden');
                });
            });

            closePBtn.addEventListener('click', () => pModal.classList.add('hidden'));
            pModal.addEventListener('click', (e) => {
                if (e.target === pModal) pModal.classList.add('hidden');
            });
        });
    </script>
</body>

</html>