<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>For A Smile - Mengukir Senyum & Transparansi Kemanusiaan</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
</head>

<body class="bg-slate-50 text-slate-800 antialiased font-sans">

    <header class="bg-white/90 backdrop-blur-md border-b border-slate-200 sticky top-0 z-50 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('assets/image/fas-logo.png') }}" alt="Logo" class="h-9 w-auto">
                <span class="font-black text-xl text-blue-600 tracking-wide">For A Smile</span>
            </div>
            
            <nav class="hidden md:flex items-center gap-6 text-sm font-medium text-slate-600">
                <a href="#tentang" class="hover:text-blue-600 transition">Tentang Kami</a>
                <a href="#kampanye" class="hover:text-blue-600 transition">Program Donasi</a>
                <a href="#alur" class="hover:text-blue-600 transition">Alur Transparansi</a>
                <a href="#faq" class="hover:text-blue-600 transition">FAQ</a>
            </nav>

            <div>
                @auth
                    <a href="{{ route('admin.dashboard') }}"
                        class="text-sm font-semibold text-blue-600 bg-blue-50 px-4 py-2 rounded-xl hover:bg-blue-100 transition inline-flex items-center gap-1">
                        <i class="ti ti-dashboard text-base"></i> Admin Panel
                    </a>
                @else
                    <a href="{{ route('admin.login') }}" class="text-sm font-semibold text-slate-600 hover:text-blue-600 transition mr-4">Masuk</a>
                    <span class="text-xs text-slate-400 font-medium bg-slate-100 px-3 py-1.5 rounded-lg">Mode Uji Coba</span>
                @endauth
            </div>
        </div>
    </header>

    <section class="relative bg-white overflow-hidden border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 py-16 sm:py-24 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="space-y-6 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-full text-xs font-bold uppercase tracking-wider">
                    <i class="ti ti-circle-check-filled animate-pulse text-sm"></i> Akuntabilitas Sistem Terverifikasi
                </div>
                <h1 class="text-3xl sm:text-5xl font-black text-slate-900 tracking-tight leading-tight">
                    Mengubah Kepedulian Menjadi <span class="text-blue-600">Senyuman Nyata</span>
                </h1>
                <p class="text-slate-500 text-base sm:text-lg leading-relaxed max-w-xl mx-auto lg:mx-0">
                    For A Smile hadir sebagai jembatan kebaikan yang menghubungkan Anda dengan para penerima manfaat melalui sistem pelaporan penyaluran yang real-time dan 100% transparan.
                </p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center lg:justify-start">
                    <a href="#kampanye" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl shadow-md shadow-blue-600/10 transition text-center text-sm">
                        Mulai Berdonasi
                    </a>
                    <a href="#tentang" class="border border-slate-300 hover:bg-slate-50 text-slate-700 font-semibold py-3 px-6 rounded-xl transition text-center text-sm">
                        Pelajari Visi Kami
                    </a>
                </div>
            </div>
            
            <div class="relative flex justify-center">
                <div class="absolute -inset-4 bg-gradient-to-tr from-blue-500 to-indigo-500 rounded-3xl blur-2xl opacity-10 transform -rotate-3"></div>
                <div class="relative w-full max-w-md bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl shadow-xl text-white p-8 overflow-hidden min-h-[300px] flex flex-col justify-between">
                    <div class="absolute -right-10 -bottom-10 w-44 h-44 bg-white/5 rounded-full blur-xl"></div>
                    <div class="flex justify-between items-start">
                        <i class="ti ti-heart-handshake text-5xl text-blue-100 opacity-90"></i>
                        <span class="text-xs bg-white/20 px-3 py-1 rounded-full font-medium tracking-wide">Midtrans Sandbox Active</span>
                    </div>
                    <div class="space-y-2 mt-8">
                        <h3 class="text-2xl font-extrabold tracking-tight">Gotong Royong Modern</h3>
                        <p class="text-sm text-blue-100/95 leading-relaxed">
                            Setiap donasi masuk divalidasi otomatis oleh sistem Midtrans Snap API dan dialokasikan ke program kerja bulanan yang terukur.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white py-10 border-b border-slate-200/60">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div class="p-4 space-y-1">
                <div class="text-3xl font-black text-blue-600">{{ count($campaigns) }}</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kampanye Aktif</div>
            </div>
            <div class="p-4 space-y-1 border-l border-slate-100">
                <div class="text-3xl font-black text-slate-900">
                    Rp{{ number_format($campaigns->sum('total_collected'), 0, ',', '.') }}
                </div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Dana Terkumpul</div>
            </div>
            <div class="p-4 space-y-1 border-l border-slate-100">
                <div class="text-3xl font-black text-slate-900">
                    {{ $campaigns->sum(fn($c) => $c->programs->count()) }}
                </div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Sub-Program Terencana</div>
            </div>
            <div class="p-4 space-y-1 border-l border-slate-100">
                <div class="text-3xl font-black text-emerald-600">100%</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pencatatan Otomatis</div>
            </div>
        </div>
    </section>

    <section id="tentang" class="max-w-7xl mx-auto px-4 py-16 sm:py-24">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <div class="space-y-3">
                <h2 class="text-xs font-bold text-blue-600 uppercase tracking-widest">Siapa Kami</h2>
                <h3 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Fokus Pada Transparansi dan Efektivitas Distribusi</h3>
                <p class="text-slate-500 text-sm sm:text-base leading-relaxed">
                    Kami percaya masalah terbesar gerakan sosial saat ini bukanlah kurangnya orang baik, melainkan kekhawatiran atas transparansi penyaluran dana donasi.
                </p>
            </div>
            
            <div class="bg-white border border-slate-200/80 p-6 rounded-2xl shadow-xs space-y-4">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-2xl">
                    <i class="ti ti-shield-check"></i>
                </div>
                <h4 class="font-bold text-lg text-slate-900">Validasi Data Akurat</h4>
                <p class="text-slate-500 text-sm leading-relaxed">
                    Penerima manfaat (beneficiaries) diverifikasi ketat secara berkala oleh tim internal kami untuk memastikan bantuan jatuh ke tangan yang berhak.
                </p>
            </div>

            <div class="bg-white border border-slate-200/80 p-6 rounded-2xl shadow-xs space-y-4">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-2xl">
                    <i class="ti ti-receipt"></i>
                </div>
                <h4 class="font-bold text-lg text-slate-900">Laporan Audit Terbuka</h4>
                <p class="text-slate-500 text-sm leading-relaxed">
                    Setiap sen alokasi dana untuk logistik, sembako, maupun santunan tercatat secara publik dan dapat ditelusuri lewat tombol "Laporan Dana" pada card kampanye.
                </p>
            </div>
        </div>
    </section>

    <section id="kampanye" class="bg-slate-100 py-16 sm:py-24 border-y border-slate-200/60 shadow-inner">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-14 space-y-2">
                <h2 class="text-xs font-bold text-blue-600 uppercase tracking-widest">Katalog Utama</h2>
                <h3 class="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight">Salurkan Kebaikan Anda</h3>
                <p class="text-slate-500 text-sm">Geser untuk menjelajahi daftar kampanye kemanusiaan aktif dan agenda sub-program kerja bulan ini.</p>
            </div>

            <div x-data="{ 
                activeSlide: 0,
                totalSlides: {{ count($campaigns) }},
                next() { this.activeSlide = this.activeSlide === this.totalSlides - 1 ? 0 : this.activeSlide + 1 },
                prev() { this.activeSlide = this.activeSlide === 0 ? this.totalSlides - 1 : this.activeSlide - 1 }
            }" class="relative max-w-4xl mx-auto">
                
                @if(count($campaigns) > 0)
                    <div class="overflow-hidden rounded-3xl bg-white p-4 sm:p-6 border border-slate-200/80 shadow-md">
                        <div class="flex transition-transform duration-500 ease-out" 
                             :style="`transform: translateX(-${activeSlide * 100}%)`">
                            
                            @foreach($campaigns as $campaign)
                                <div class="w-full flex-shrink-0 px-1 sm:px-3">
                                    <div class="flex flex-col md:flex-row group min-h-[380px] gap-6">

                                        <div class="h-48 md:h-auto md:w-5/12 bg-slate-100 flex items-center justify-center relative overflow-hidden rounded-2xl flex-shrink-0 border border-slate-100">
                                            @if($campaign->image_url)
                                                <img src="{{ asset('storage/' . $campaign->image_url) }}" alt="{{ $campaign->title }}"
                                                    class="w-full h-full object-cover group-hover:scale-102 transition duration-500 ease-out"
                                                    onerror="this.onerror=null; this.src='data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22100%25%22 height=%22100%25%22><rect width=%22100%25%22 height=%22100%25%22 fill=%22%232563eb%22/><text x=%2250%25%22 y=%2250%25%22 font-family=%22sans-serif%22 font-size=%2224%22 fill=%22white%22 text-anchor=%22middle%22 dy=%22.3em%22>❤️ For A Smile</text></svg>';">
                                            @else
                                                <div class="w-full h-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center p-6">
                                                    <i class="ti ti-heart-handshake text-white text-6xl opacity-80"></i>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="flex-1 flex flex-col justify-between py-1">
                                            <div>
                                                <h3 class="font-bold text-slate-900 text-xl leading-snug mb-2 line-clamp-2">
                                                    {{ $campaign->title }}
                                                </h3>
                                                <p class="text-slate-500 text-sm line-clamp-3 mb-4">
                                                    {{ $campaign->description ?? 'Tidak ada deskripsi tambahan untuk kampanye ini.' }}
                                                </p>

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

                                            <div class="pt-4 border-t border-slate-100 grid grid-cols-2 gap-3">
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
                                </div>
                            @endforeach

                        </div>
                    </div>

                    <button @click="prev()" class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 sm:-translate-x-6 bg-white hover:bg-slate-50 text-slate-700 w-11 h-11 rounded-full flex items-center justify-center shadow-md border border-slate-200 z-10 transition cursor-pointer">
                        ❮
                    </button>
                    <button @click="next()" class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 sm:translate-x-6 bg-white hover:bg-slate-50 text-slate-700 w-11 h-11 rounded-full flex items-center justify-center shadow-md border border-slate-200 z-10 transition cursor-pointer">
                        ❯
                    </button>

                    <div class="flex justify-center gap-2 mt-6">
                        <template x-for="(item, index) in totalSlides" :key="index">
                            <button @click="activeSlide = index" 
                                    class="h-2 rounded-full transition-all duration-300 cursor-pointer"
                                    :class="activeSlide === index ? 'bg-blue-600 w-6' : 'bg-slate-300 w-2'"></button>
                        </template>
                    </div>
                @else
                    <div class="bg-white border border-dashed border-slate-300 rounded-3xl p-12 text-center text-slate-500">
                        <i class="ti ti-folder-off text-4xl mb-2 text-slate-300 block"></i> Belum ada data kampanye aktif saat ini.
                    </div>
                @endif

            </div>
        </div>
    </section>

    <section id="alur" class="max-w-7xl mx-auto px-4 py-16 sm:py-24 space-y-12">
        <div class="text-center max-w-xl mx-auto space-y-2">
            <h2 class="text-xs font-bold text-blue-600 uppercase tracking-widest">Bagaimana Sistem Bekerja</h2>
            <h3 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">4 Langkah Transparansi Penuh</h3>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 relative">
            <div class="space-y-2 relative">
                <div class="w-10 h-10 rounded-xl bg-blue-600 text-white font-bold flex items-center justify-center shadow">1</div>
                <h4 class="font-bold text-base text-slate-900 pt-2">Pilih Kampanye</h4>
                <p class="text-slate-500 text-xs leading-relaxed">Pilih program bantuan sosial atau keagamaan yang ingin Anda dukung pada katalog.</p>
            </div>
            <div class="space-y-2">
                <div class="w-10 h-10 rounded-xl bg-blue-600 text-white font-bold flex items-center justify-center shadow">2</div>
                <h4 class="font-bold text-base text-slate-900 pt-2">Pembayaran Elektronik</h4>
                <p class="text-slate-500 text-xs leading-relaxed">Gunakan Midtrans Snap API untuk menyelesaikan pembayaran aman via QRIS, E-Wallet, atau Transfer.</p>
            </div>
            <div class="space-y-2">
                <div class="w-10 h-10 rounded-xl bg-blue-600 text-white font-bold flex items-center justify-center shadow">3</div>
                <h4 class="font-bold text-base text-slate-900 pt-2">Pencatatan Real-Time</h4>
                <p class="text-slate-500 text-xs leading-relaxed">Webhook Midtrans memverifikasi dana secara instan untuk memperbarui grafik progress bar.</p>
            </div>
            <div class="space-y-2">
                <div class="w-10 h-10 rounded-xl bg-blue-600 text-white font-bold flex items-center justify-center shadow">4</div>
                <h4 class="font-bold text-base text-slate-900 pt-2">Laporan Distribusi</h4>
                <p class="text-slate-500 text-xs leading-relaxed">Tim mendistribusikan santunan dan mengunggah log dokumentasi yang bisa Anda pantau kapan saja.</p>
            </div>
        </div>
    </section>

    <section id="faq" class="bg-white py-16 sm:py-24 border-t border-slate-200/60">
        <div class="max-w-3xl mx-auto px-4 space-y-8">
            <div class="text-center space-y-2">
                <h2 class="text-xs font-bold text-blue-600 uppercase tracking-widest">Pertanyaan Umum</h2>
                <h3 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Sering Ditanyakan Donatur</h3>
            </div>

            <div x-data="{ activeFaq: null }" class="space-y-3">
                <div class="border border-slate-200 rounded-xl overflow-hidden bg-slate-50">
                    <button @click="activeFaq = (activeFaq === 1 ? null : 1)" class="w-full text-left p-4 font-bold text-sm sm:text-base flex justify-between items-center text-slate-800 cursor-pointer">
                        Apakah donasi di platform ini langsung terverifikasi otomatis?
                        <i class="ti text-lg" :class="activeFaq === 1 ? 'ti-minus' : 'ti-plus'"></i>
                    </button>
                    <div x-show="activeFaq === 1" x-collapse class="p-4 pt-0 text-xs sm:text-sm text-slate-500 leading-relaxed border-t border-slate-200/60 bg-white">
                        Ya. Kami menggunakan integrasi gerbang pembayaran Midtrans Snap API. Begitu transaksi Anda dinyatakan sukses oleh bank atau penyedia e-wallet, sistem kami menerima data via webhook dan memperbarui progress capaian secara real-time.
                    </div>
                </div>

                <div class="border border-slate-200 rounded-xl overflow-hidden bg-slate-50">
                    <button @click="activeFaq = (activeFaq === 2 ? null : 2)" class="w-full text-left p-4 font-bold text-sm sm:text-base flex justify-between items-center text-slate-800 cursor-pointer">
                        Bagaimana cara saya melihat bukti penyaluran dana yang sudah saya berikan?
                        <i class="ti text-lg" :class="activeFaq === 2 ? 'ti-minus' : 'ti-plus'"></i>
                    </button>
                    <div x-show="activeFaq === 2" x-collapse class="p-4 pt-0 text-xs sm:text-sm text-slate-500 leading-relaxed border-t border-slate-200/60 bg-white">
                        Anda dapat menekan tombol <strong>"Laporan Dana"</strong> yang terletak di setiap card kampanye. Sistem akan memunculkan popup berisi ringkasan alokasi logistik beserta nama-nama penerima santunan terverifikasi.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="webDistributionModal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50 p-4">
        <div class="bg-white w-full max-w-md rounded-2xl p-6 relative">
            <button id="closeWebModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-xl font-bold cursor-pointer">&times;</button>
            <div id="modalDynamicContent">
                <p class="text-center text-gray-500 text-sm animate-pulse">Memuat data...</p>
            </div>
        </div>
    </div>

    <div id="programDetailsModal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50 p-4">
        <div class="bg-white w-full max-w-lg rounded-2xl p-6 relative max-h-[85vh] flex flex-col">
            <button id="closeProgramModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-xl font-bold cursor-pointer">&times;</button>
            
            <div class="flex flex-col h-full overflow-hidden">
                <h3 id="programModalTitle" class="font-bold text-xl text-gray-800 mb-1 pr-6 leading-tight"></h3>
                
                <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4 mt-1">
                    <p class="text-xs text-gray-500">Daftar Agenda & Sub-Program Kegiatan Kerja</p>
                    <span id="programModalCount" class="text-[11px] font-bold bg-blue-50 text-blue-600 px-2.5 py-1 rounded-lg"></span>
                </div>
                
                <div id="programModalList" class="space-y-4 flex-1 overflow-y-auto pr-1 pb-2"></div>
            </div>
        </div>
    </div>

    <footer class="bg-slate-900 text-slate-400 py-12 border-t border-slate-800 text-sm">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-2">
                <span class="font-black text-lg text-white">For A Smile</span>
                <span class="text-xs text-slate-500">| © {{ date('Y') }} All Rights Reserved.</span>
            </div>
            <p class="text-xs text-slate-500 text-center md:text-right max-w-md">
                Platform ekosistem donasi akuntabel yang dirancang khusus untuk meminimalkan kendala administrasi publik dan mengoptimalkan pendistribusian dana sosial secara presisi.
            </p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Kontrol Modal Laporan Dana (AJAX)
            const modal = document.getElementById('webDistributionModal');
            const modalContent = document.getElementById('modalDynamicContent');
            const closeBtn = document.getElementById('closeWebModal');

            document.querySelectorAll('.btn-web-distribusi').forEach(button => {
                button.addEventListener('click', function () {
                    const campaignId = this.getAttribute('data-id');
                    modalContent.innerHTML = '<p class="text-center text-gray-500 text-sm py-4 animate-pulse">Sedang mengambil riwayat penyaluran...</p>';
                    modal.classList.remove('hidden');

                    fetch(`/campaigns/${campaignId}/distribution-modal`)
                        .then(response => {
                            if (!response.ok) throw new Error();
                            return response.text();
                        })
                        .then(htmlOutput => {
                            modalContent.innerHTML = htmlOutput;
                        })
                        .catch(err => {
                            modalContent.innerHTML = '<p class="text-center text-red-500 text-sm py-4">Gagal memuat transparansi laporan dana.</p>';
                        });
                });
            });

            closeBtn.addEventListener('click', () => modal.classList.add('hidden'));
            modal.addEventListener('click', (e) => {
                if (e.target === modal) modal.classList.add('hidden');
            });

            // Kontrol Modal Detail Sub-Program Kerja (Card Render)
            const pModal = document.getElementById('programDetailsModal');
            const pTitle = document.getElementById('programModalTitle');
            const pCount = document.getElementById('programModalCount');
            const pList = document.getElementById('programModalList');
            const closePBtn = document.getElementById('closeProgramModal');

            document.querySelectorAll('.btn-lihat-semu-program').forEach(button => {
                button.addEventListener('click', function() {
                    const title = this.getAttribute('data-title');
                    const programs = JSON.parse(this.getAttribute('data-programs'));

                    pTitle.innerText = title;
                    pCount.innerText = `${programs.length} Program`;

                    pList.innerHTML = '';
                    programs.forEach(prog => {
                        let namaProgram = prog.program_name || 'Program Kerja';
                        let imgHtml = prog.image_url 
                            ? `<img src="/storage/${prog.image_url}" alt="${namaProgram}" class="w-full h-full object-cover">`
                            : `<div class="w-full h-full bg-gradient-to-br from-blue-500/10 to-indigo-600/10 flex items-center justify-center"><i class="ti ti-circle-check text-blue-600 text-xl"></i></div>`;

                        pList.innerHTML += `
                            <div class="bg-white border border-slate-100 rounded-xl overflow-hidden shadow-xs flex gap-3 p-3 hover:border-slate-200 transition duration-200">
                                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-slate-50 rounded-lg overflow-hidden flex-shrink-0 border border-slate-100">${imgHtml}</div>
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