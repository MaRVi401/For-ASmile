<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>For A Smile - Mengukir Senyum & Transparansi Kemanusiaan</title>
    <link rel="icon" href="{{ asset('assets/image/fas-logo.png') }}">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
</head>

<body class="bg-slate-50 text-slate-800 antialiased font-sans" x-data="{ mobileMenuOpen: false }">

    <!-- 1. NAVBAR RESPONSIVE WITH MOBILE HAMBURGER -->
    <header class="bg-white/90 backdrop-blur-md border-b border-slate-200 sticky top-0 z-50 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center gap-2 sm:gap-3">
                <img src="{{ asset('assets/image/fas-logo.png') }}" alt="Logo" class="h-8 sm:h-9 w-auto">
                <span class="font-black text-base sm:text-xl text-blue-600 tracking-wide">For A Smile</span>
            </div>
            
            <!-- Menu Navigasi Desktop -->
            <nav class="hidden md:flex items-center gap-6 text-sm font-medium text-slate-600">
                <a href="#tentang" class="hover:text-blue-600 transition">Tentang Kami</a>
                <a href="#kampanye" class="hover:text-blue-600 transition">Program Donasi</a>
                <a href="#alur" class="hover:text-blue-600 transition">Alur Transparansi</a>
                <a href="#faq" class="hover:text-blue-600 transition">FAQ</a>
            </nav>

            <div class="flex items-center gap-2">
                @auth
                    <a href="{{ route('admin.dashboard') }}"
                        class="text-xs sm:text-sm font-semibold text-blue-600 bg-blue-50 px-3 py-2 sm:px-4 rounded-xl hover:bg-blue-100 transition inline-flex items-center">
                        Admin Panel
                    </a>
                @endauth

                <!-- Hamburger Button Mobile Only -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="md:hidden text-slate-600 hover:text-blue-600 p-2 rounded-lg focus:outline-hidden">
                    <i class="ti text-2xl" :class="mobileMenuOpen ? 'ti-x' : 'ti-menu-2'"></i>
                </button>
            </div>
        </div>

        <!-- Menu Drawer Mobile -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="md:hidden border-b border-slate-200 bg-white px-4 pt-2 pb-4 space-y-2 shadow-xs absolute w-full left-0 z-40" 
             style="display: none;">
            <a href="#tentang" @click="mobileMenuOpen = false" class="block py-2 px-3 text-sm font-medium text-slate-600 rounded-lg hover:bg-slate-50 hover:text-blue-600">Tentang Kami</a>
            <a href="#kampanye" @click="mobileMenuOpen = false" class="block py-2 px-3 text-sm font-medium text-slate-600 rounded-lg hover:bg-slate-50 hover:text-blue-600">Program Donasi</a>
            <a href="#alur" @click="mobileMenuOpen = false" class="block py-2 px-3 text-sm font-medium text-slate-600 rounded-lg hover:bg-slate-50 hover:text-blue-600">Alur Transparansi</a>
            <a href="#faq" @click="mobileMenuOpen = false" class="block py-2 px-3 text-sm font-medium text-slate-600 rounded-lg hover:bg-slate-50 hover:text-blue-600">FAQ</a>
        </div>
    </header>

    <!-- 2. HERO SECTION RESPONSIVE -->
    <section class="relative bg-white overflow-hidden border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 py-10 sm:py-16 lg:py-24 grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">
            <div class="space-y-4 sm:space-y-6 text-center lg:text-left order-last lg:order-first">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-full text-[11px] sm:text-xs font-bold uppercase tracking-wider">
                    <i class="ti ti-circle-check-filled animate-pulse text-xs sm:text-sm"></i> Akuntabilitas Sistem Terverifikasi
                </div>
                <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black text-slate-900 tracking-tight leading-tight">
                    Mengubah Kepedulian Menjadi <span class="text-blue-600">Senyuman Nyata</span>
                </h1>
                <p class="text-slate-500 text-sm sm:text-base lg:text-lg leading-relaxed max-w-xl mx-auto lg:mx-0">
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
            
            <div class="relative flex justify-center order-first lg:order-last">
                <div class="absolute -inset-4 bg-gradient-to-tr from-blue-500 to-indigo-500 rounded-3xl blur-2xl opacity-10 transform -rotate-3"></div>
                <div class="relative w-full max-w-md bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl shadow-xl text-white p-6 sm:p-8 overflow-hidden min-h-[220px] sm:min-h-[300px] flex flex-col justify-between">
                    <div class="absolute -right-10 -bottom-10 w-44 h-44 bg-white/5 rounded-full blur-xl"></div>
                    <div class="flex justify-between items-start">
                        <i class="ti ti-heart-handshake text-4xl sm:text-5xl text-blue-100 opacity-90"></i>
                        <span class="text-[10px] sm:text-xs bg-white/20 px-2.5 py-1 rounded-full font-medium tracking-wide">Midtrans Sandbox Active</span>
                    </div>
                    <div class="space-y-1 sm:space-y-2 mt-6 sm:mt-8">
                        <h3 class="text-xl sm:text-2xl font-extrabold tracking-tight">Gotong Royong Modern</h3>
                        <p class="text-xs sm:text-sm text-blue-100/95 leading-relaxed">
                            Setiap donasi masuk divalidasi otomatis oleh sistem Midtrans Snap API dan dialokasikan ke program kerja bulanan yang terukur.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. METRIK DAMPAK RESPONSIVE GRID -->
    <section class="bg-white py-6 sm:py-10 border-b border-slate-200/60">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 text-center">
            <div class="p-2 sm:p-4 space-y-0.5 sm:space-y-1">
                <div class="text-xl sm:text-3xl font-black text-blue-600">{{ count($campaigns) }}</div>
                <div class="text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-wider">Kampanye Aktif</div>
            </div>
            <div class="p-2 sm:p-4 space-y-0.5 sm:space-y-1 border-l border-slate-100">
                <div class="text-xl sm:text-3xl font-black text-slate-900 truncate px-1">
                    Rp{{ number_format($campaigns->sum('total_collected'), 0, ',', '.') }}
                </div>
                <div class="text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-wider">Total Dana Terkumpul</div>
            </div>
            <div class="p-2 sm:p-4 space-y-0.5 sm:space-y-1 border-l border-slate-100">
                <div class="text-xl sm:text-3xl font-black text-slate-900">
                    {{ $campaigns->sum(fn($c) => $c->programs->count()) }}
                </div>
                <div class="text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-wider">Sub-Program Terencana</div>
            </div>
            <div class="p-2 sm:p-4 space-y-0.5 sm:space-y-1 border-l border-slate-100">
                <div class="text-xl sm:text-3xl font-black text-emerald-600">100%</div>
                <div class="text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-wider">Pencatatan Otomatis</div>
            </div>
        </div>
    </section>

    <!-- 4. VISI MISI RESPONSIVE -->
    <section id="tentang" class="max-w-7xl mx-auto px-4 py-12 sm:py-24">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8 items-start">
            <div class="space-y-2 sm:space-y-3 text-center lg:text-left">
                <h2 class="text-xs font-bold text-blue-600 uppercase tracking-widest">Siapa Kami</h2>
                <h3 class="text-xl sm:text-3xl font-black text-slate-900 tracking-tight">Fokus Pada Transparansi dan Efektivitas Distribusi</h3>
                <p class="text-slate-500 text-xs sm:text-base leading-relaxed">
                    Kami percaya masalah terbesar gerakan sosial saat ini bukanlah kurangnya orang baik, melainkan kekhawatiran atas transparansi penyaluran dana donasi.
                </p>
            </div>
            
            <div class="bg-white border border-slate-200/80 p-5 sm:p-6 rounded-2xl shadow-xs space-y-3 sm:space-y-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl sm:text-2xl">
                    <i class="ti ti-shield-check"></i>
                </div>
                <h4 class="font-bold text-base sm:text-lg text-slate-900">Validasi Data Akurat</h4>
                <p class="text-slate-500 text-xs sm:text-sm leading-relaxed">
                    Penerima manfaat (beneficiaries) diverifikasi ketat secara berkala oleh tim internal kami untuk memastikan bantuan jatuh ke tangan yang berhak.
                </p>
            </div>

            <div class="bg-white border border-slate-200/80 p-5 sm:p-6 rounded-2xl shadow-xs space-y-3 sm:space-y-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl sm:text-2xl">
                    <i class="ti ti-receipt"></i>
                </div>
                <h4 class="font-bold text-base sm:text-lg text-slate-900">Laporan Audit Terbuka</h4>
                <p class="text-slate-500 text-xs sm:text-sm leading-relaxed">
                    Setiap sen alokasi dana untuk logistik, sembako, maupun santunan tercatat secara publik dan dapat ditelusuri lewat tombol "Laporan Dana" pada card kampanye.
                </p>
            </div>
        </div>
    </section>

    <!-- 5. CAROUSEL DONASI RESPONSIVE (FLEX LAYOUT & MAX ARROW SPACE) -->
    <section id="kampanye" class="bg-slate-100 py-12 sm:py-24 border-y border-slate-200/60 shadow-inner">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center max-w-2xl mx-auto mb-8 sm:mb-14 space-y-1 sm:space-y-2">
                <h2 class="text-xs font-bold text-blue-600 uppercase tracking-widest">Katalog Utama</h2>
                <h3 class="text-xl sm:text-4xl font-black text-slate-900 tracking-tight">Laporan Donasi</h3>
                <p class="text-slate-500 text-xs sm:text-sm">Geser untuk menjelajahi daftar kampanye kemanusiaan aktif dan agenda sub-program kerja bulan ini.</p>
            </div>

            <div x-data="{ 
                activeSlide: 0,
                totalSlides: {{ count($campaigns) }},
                next() { this.activeSlide = this.activeSlide === this.totalSlides - 1 ? 0 : this.activeSlide + 1 },
                prev() { this.activeSlide = this.activeSlide === 0 ? this.totalSlides - 1 : this.activeSlide - 1 }
            }" class="relative max-w-4xl mx-auto px-2 sm:px-6">
                
                @if(count($campaigns) > 0)
                    <div class="overflow-hidden rounded-2xl sm:rounded-3xl bg-white p-3 sm:p-6 border border-slate-200/80 shadow-md">
                        <div class="flex transition-transform duration-500 ease-out" 
                             :style="`transform: translateX(-${activeSlide * 100}%)`">
                            
                            @foreach($campaigns as $campaign)
                                <div class="w-full flex-shrink-0 px-1 sm:px-3">
                                    <div class="flex flex-col lg:flex-row group gap-4 sm:gap-6">

                                        <!-- Cover Image (Responsive Scale) -->
                                        <div class="h-44 sm:h-56 lg:h-auto lg:w-5/12 bg-slate-100 flex items-center justify-center relative overflow-hidden rounded-xl flex-shrink-0 border border-slate-100">
                                            @if($campaign->image_url)
                                                <img src="{{ asset('storage/' . $campaign->image_url) }}" alt="{{ $campaign->title }}"
                                                    class="w-full h-full object-cover group-hover:scale-102 transition duration-500 ease-out"
                                                    onerror="this.onerror=null; this.src='data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22100%25%22 height=%22100%25%22><rect width=%22100%25%22 height=%22100%25%22 fill=%22%232563eb%22/><text x=%2250%25%22 y=%2250%25%22 font-family=%22sans-serif%22 font-size=%2224%22 fill=%22white%22 text-anchor=%22middle%22 dy=%22.3em%22>❤️ For A Smile</text></svg>';">
                                            @else
                                                <div class="w-full h-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center p-6">
                                                    <i class="ti ti-heart-handshake text-white text-5xl lg:text-6xl opacity-80"></i>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Content (Responsive Padding & Font Sizing) -->
                                        <div class="flex-1 flex flex-col justify-between py-1 space-y-4">
                                            <div>
                                                <h3 class="font-bold text-slate-900 text-base sm:text-xl leading-snug mb-1.5 line-clamp-2">
                                                    {{ $campaign->title }}
                                                </h3>
                                                <p class="text-slate-500 text-xs sm:text-sm line-clamp-3 mb-3">
                                                    {{ $campaign->description ?? 'Tidak ada deskripsi tambahan untuk kampanye ini.' }}
                                                </p>

                                                <!-- Agenda Kerja -->
                                                <div class="mb-3.5 bg-slate-50 p-3 rounded-xl border border-slate-100">
                                                    <div class="flex items-center justify-between mb-2">
                                                        <span class="text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-wide">Target Agenda Kerja:</span>
                                                        <span class="text-[10px] font-semibold bg-blue-50 text-blue-600 px-2 py-0.5 rounded-md">
                                                            {{ $campaign->programs->count() }} Program
                                                        </span>
                                                    </div>
                                                    @if($campaign->programs->isEmpty())
                                                        <p class="text-[11px] text-slate-400 italic">Belum ada rincian sub-program bulan ini.</p>
                                                    @else
                                                        <ul class="space-y-1 max-h-20 overflow-y-auto pr-1 mb-2">
                                                            @foreach($campaign->programs->take(2) as $program)
                                                                <li class="text-[11px] sm:text-xs text-slate-600 flex items-start gap-1.5">
                                                                    <i class="ti ti-circle-check text-emerald-500 mt-0.5 text-xs sm:text-sm"></i>
                                                                    <span class="line-clamp-1">{{ $program->program_name }}</span>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                        <button type="button" 
                                                                class="btn-lihat-semu-program w-full text-center text-[11px] sm:text-xs text-blue-600 bg-blue-50 hover:bg-blue-100 font-bold py-2 px-3 rounded-lg transition inline-flex items-center justify-center gap-1 cursor-pointer"
                                                                data-title="{{ $campaign->title }}"
                                                                data-programs="{{ json_encode($campaign->programs) }}">
                                                            <i class="ti ti-list-details text-xs sm:text-sm"></i> Lihat Detail Program
                                                        </button>
                                                    @endif
                                                </div>

                                                <!-- Progress Pengumpulan Dana -->
                                                <div class="space-y-1.5">
                                                    <div class="flex justify-between items-end text-[11px] sm:text-sm">
                                                        <div>
                                                            <span class="text-slate-400 block text-[9px] sm:text-[10px] uppercase font-bold tracking-wider">Terkumpul</span>
                                                            <span class="font-bold text-blue-600">Rp{{ number_format($campaign->total_collected, 0, ',', '.') }}</span>
                                                        </div>
                                                        <div class="text-right">
                                                            <span class="text-slate-400 block text-[9px] sm:text-[10px] uppercase font-bold tracking-wider">Target</span>
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

                                                    <div class="flex justify-between items-center text-[10px] sm:text-[11px] text-slate-400 font-medium">
                                                        <span>Progress capaian</span>
                                                        <span class="text-blue-600 font-bold bg-blue-50 px-1.5 py-0.5 rounded">{{ $percentage }}%</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Button Action Grid -->
                                            <div class="pt-3 border-t border-slate-100 grid grid-cols-1 gap-2 sm:gap-3">
                                                {{-- <span class="flex items-center justify-center gap-1.5 bg-blue-600 text-white font-semibold py-2.5 px-2 rounded-xl text-xs sm:text-sm shadow-xs shadow-blue-600/10 w-full select-none">
                                                    <i class="ti ti-mood-smile text-sm sm:text-base"></i> For A Smile
                                                </span> --}}

                                                <a href="{{ route('donation.create', $campaign->id) }}"
                                                    class="flex items-center justify-center gap-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-2 rounded-xl transition cursor-pointer text-xs sm:text-sm shadow-xs shadow-blue-600/10">
                                                    <i class="ti ti-heart text-sm sm:text-base"></i> Donasi
                                                </a>

                                                <button type="button"
                                                    class="btn-web-distribusi flex items-center justify-center gap-1.5 border border-blue-600 hover:bg-blue-50 text-blue-600 font-semibold py-2.5 px-2 rounded-xl transition cursor-pointer text-xs sm:text-sm w-full"
                                                    data-id="{{ $campaign->id }}">
                                                    <i class="ti ti-report-money text-sm sm:text-base"></i> Laporan Dana
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>

                    <!-- Arrow Controls (Di luar area card pada desktop, pas di margin pada mobile) -->
                    <button @click="prev()" class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-1 sm:-translate-x-4 bg-white hover:bg-slate-50 text-slate-700 w-9 h-9 sm:w-11 sm:h-11 rounded-full flex items-center justify-center shadow-md border border-slate-200 z-10 transition cursor-pointer text-xs sm:text-base">
                        ❮
                    </button>
                    <button @click="next()" class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-1 sm:translate-x-4 bg-white hover:bg-slate-50 text-slate-700 w-9 h-9 sm:w-11 sm:h-11 rounded-full flex items-center justify-center shadow-md border border-slate-200 z-10 transition cursor-pointer text-xs sm:text-base">
                        ❯
                    </button>

                    <!-- Indicators Dots -->
                    <div class="flex justify-center gap-2 mt-4 sm:mt-6">
                        <template x-for="(item, index) in totalSlides" :key="index">
                            <button @click="activeSlide = index" 
                                    class="h-1.5 sm:h-2 rounded-full transition-all duration-300 cursor-pointer"
                                    :class="activeSlide === index ? 'bg-blue-600 w-5 sm:w-6' : 'bg-slate-300 w-1.5 sm:w-2'"></button>
                        </template>
                    </div>
                @else
                    <div class="bg-white border border-dashed border-slate-300 rounded-2xl p-8 sm:p-12 text-center text-slate-500">
                        <i class="ti ti-folder-off text-3xl sm:text-4xl mb-1 sm:mb-2 text-slate-300 block"></i> Belum ada data kampanye aktif saat ini.
                    </div>
                @endif

            </div>
        </div>
    </section>

    <!-- 6. ALUR DONASI RESPONSIVE GRID -->
    <section id="alur" class="max-w-7xl mx-auto px-4 py-12 sm:py-24 space-y-8 sm:space-y-12">
        <div class="text-center max-w-xl mx-auto space-y-1 sm:space-y-2">
            <h2 class="text-xs font-bold text-blue-600 uppercase tracking-widest">Bagaimana Sistem Bekerja</h2>
            <h3 class="text-xl sm:text-3xl font-black text-slate-900 tracking-tight">4 Langkah Transparansi Penuh</h3>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8 relative">
            <div class="space-y-1 sm:space-y-2 relative">
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-blue-600 text-white font-bold flex items-center justify-center shadow text-sm sm:text-base">1</div>
                <h4 class="font-bold text-sm sm:text-base text-slate-900 pt-1">Pilih Kampanye</h4>
                <p class="text-slate-500 text-xs leading-relaxed">Pilih program bantuan sosial atau keagamaan yang ingin Anda dukung pada katalog.</p>
            </div>
            <div class="space-y-1 sm:space-y-2">
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-blue-600 text-white font-bold flex items-center justify-center shadow text-sm sm:text-base">2</div>
                <h4 class="font-bold text-sm sm:text-base text-slate-900 pt-1">Pembayaran Elektronik</h4>
                <p class="text-slate-500 text-xs leading-relaxed">Gunakan Midtrans Snap API untuk menyelesaikan pembayaran aman via QRIS, E-Wallet, atau Transfer.</p>
            </div>
            <div class="space-y-1 sm:space-y-2">
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-blue-600 text-white font-bold flex items-center justify-center shadow text-sm sm:text-base">3</div>
                <h4 class="font-bold text-sm sm:text-base text-slate-900 pt-1">Pencatatan Real-Time</h4>
                <p class="text-slate-500 text-xs leading-relaxed">Webhook Midtrans memverifikasi dana secara instan untuk memperbarui grafik progress bar.</p>
            </div>
            <div class="space-y-1 sm:space-y-2">
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-blue-600 text-white font-bold flex items-center justify-center shadow text-sm sm:text-base">4</div>
                <h4 class="font-bold text-sm sm:text-base text-slate-900 pt-1">Laporan Distribusi</h4>
                <p class="text-slate-500 text-xs leading-relaxed">Tim mendistribusikan santunan dan mengunggah log dokumentasi yang bisa Anda pantau kapan saja.</p>
            </div>
        </div>
    </section>

    <!-- 7. SEKSI FAQ ACCORDION -->
    <section id="faq" class="bg-white py-12 sm:py-24 border-t border-slate-200/60">
        <div class="max-w-3xl mx-auto px-4 space-y-6 sm:space-y-8">
            <div class="text-center space-y-1 sm:space-y-2">
                <h2 class="text-xs font-bold text-blue-600 uppercase tracking-widest">Pertanyaan Umum</h2>
                <h3 class="text-xl sm:text-3xl font-black text-slate-900 tracking-tight">Sering Ditanyakan Donatur</h3>
            </div>

            <div x-data="{ activeFaq: null }" class="space-y-3">
                <div class="border border-slate-200 rounded-xl overflow-hidden bg-slate-50">
                    <button @click="activeFaq = (activeFaq === 1 ? null : 1)" class="w-full text-left p-3.5 sm:p-4 font-bold text-xs sm:text-base flex justify-between items-center text-slate-800 cursor-pointer gap-2">
                        <span>Apakah donasi di platform ini langsung terverifikasi otomatis?</span>
                        <i class="ti text-base sm:text-lg shrink-0" :class="activeFaq === 1 ? 'ti-minus' : 'ti-plus'"></i>
                    </button>
                    <div x-show="activeFaq === 1" x-collapse class="p-3.5 sm:p-4 pt-0 text-xs sm:text-sm text-slate-500 leading-relaxed border-t border-slate-200/60 bg-white">
                        Ya. Kami menggunakan integrasi gerbang pembayaran Midtrans Snap API. Begitu transaksi Anda dinyatakan sukses oleh bank atau penyedia e-wallet, sistem kami menerima data via webhook dan memperbarui progress capaian secara real-time.
                    </div>
                </div>

                <div class="border border-slate-200 rounded-xl overflow-hidden bg-slate-50">
                    <button @click="activeFaq = (activeFaq === 2 ? null : 2)" class="w-full text-left p-3.5 sm:p-4 font-bold text-xs sm:text-base flex justify-between items-center text-slate-800 cursor-pointer gap-2">
                        <span>Bagaimana cara saya melihat bukti penyaluran dana yang sudah saya berikan?</span>
                        <i class="ti text-base sm:text-lg shrink-0" :class="activeFaq === 2 ? 'ti-minus' : 'ti-plus'"></i>
                    </button>
                    <div x-show="activeFaq === 2" x-collapse class="p-3.5 sm:p-4 pt-0 text-xs sm:text-sm text-slate-500 leading-relaxed border-t border-slate-200/60 bg-white">
                        Anda dapat menekan tombol <strong>"Laporan Dana"</strong> yang terletak di setiap card kampanye. Sistem akan memunculkan popup berisi ringkasan alokasi logistik beserta nama-nama penerima santunan terverifikasi.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 8. MODAL RESPONSIVE 1: LAPORAN TRANSMARANSI DISTRIBUSI DANA -->
    <div id="webDistributionModal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50 p-3 sm:p-4">
        <div class="bg-white w-full max-w-md rounded-2xl p-5 sm:p-6 relative max-h-[90vh] overflow-y-auto">
            <button id="closeWebModal" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-xl font-bold cursor-pointer">&times;</button>
            <div id="modalDynamicContent" class="pt-4">
                <p class="text-center text-gray-500 text-xs sm:text-sm animate-pulse">Memuat data...</p>
            </div>
        </div>
    </div>

    <!-- 9. MODAL RESPONSIVE 2: DETAIL PROGRAM KERJA -->
    <div id="programDetailsModal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50 p-3 sm:p-4">
        <div class="bg-white w-full max-w-lg rounded-2xl p-5 sm:p-6 relative max-h-[85vh] flex flex-col overflow-hidden">
            <button id="closeProgramModal" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-xl font-bold cursor-pointer">&times;</button>
            
            <div class="flex flex-col h-full overflow-hidden pt-2">
                <h3 id="programModalTitle" class="font-bold text-base sm:text-xl text-gray-800 mb-1 pr-6 leading-tight"></h3>
                
                <div class="flex items-center justify-between border-b border-slate-100 pb-2.5 mb-3 mt-1">
                    <p class="text-[11px] sm:text-xs text-gray-500">Daftar Agenda & Sub-Program Kegiatan Kerja</p>
                    <span id="programModalCount" class="text-[10px] sm:text-[11px] font-bold bg-blue-50 text-blue-600 px-2 py-0.5 rounded-lg"></span>
                </div>
                
                <div id="programModalList" class="space-y-3 flex-1 overflow-y-auto pr-1 pb-1"></div>
            </div>
        </div>
    </div>

    <!-- 10. FOOTER RESPONSIVE -->
    <footer class="bg-slate-900 text-slate-400 py-8 sm:py-12 border-t border-slate-800 text-xs sm:text-sm">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center justify-between gap-4 sm:gap-6">
            <div class="flex items-center gap-2">
                <span class="font-black text-base sm:text-lg text-white">For A Smile</span>
                <span class="text-[11px] sm:text-xs text-slate-500">| © {{ date('Y') }} All Rights Reserved.</span>
            </div>
            <p class="text-[11px] sm:text-xs text-slate-500 text-center md:text-right max-w-md leading-relaxed">
                Platform ekosistem donasi akuntabel yang dirancang khusus untuk meminimalkan kendala administrasi publik dan mengoptimalkan pendistribusian dana sosial secara presisi.
            </p>
        </div>
    </footer>

    <!-- LOGIC JAVASCRIPT MODAL & DATA PARSING -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Kontrol Laporan Dana
            const modal = document.getElementById('webDistributionModal');
            const modalContent = document.getElementById('modalDynamicContent');
            const closeBtn = document.getElementById('closeWebModal');

            document.querySelectorAll('.btn-web-distribusi').forEach(button => {
                button.addEventListener('click', function () {
                    const campaignId = this.getAttribute('data-id');
                    modalContent.innerHTML = '<p class="text-center text-gray-500 text-xs py-4 animate-pulse">Sedang mengambil riwayat penyaluran...</p>';
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
                            modalContent.innerHTML = '<p class="text-center text-red-500 text-xs py-4">Gagal memuat transparansi laporan dana.</p>';
                        });
                });
            });

            closeBtn.addEventListener('click', () => modal.classList.add('hidden'));
            modal.addEventListener('click', (e) => {
                if (e.target === modal) modal.classList.add('hidden');
            });

            // Kontrol Rincian Agenda Sub-Program Kerja
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
                            : `<div class="w-full h-full bg-gradient-to-br from-blue-500/10 to-indigo-600/10 flex items-center justify-center"><i class="ti ti-circle-check text-blue-600 text-base sm:text-xl"></i></div>`;

                        pList.innerHTML += `
                            <div class="bg-white border border-slate-100 rounded-xl overflow-hidden shadow-xs flex gap-2.5 sm:gap-3 p-2.5 sm:p-3 hover:border-slate-200 transition duration-200">
                                <div class="w-14 h-14 sm:w-20 sm:h-20 bg-slate-50 rounded-lg overflow-hidden flex-shrink-0 border border-slate-100">${imgHtml}</div>
                                <div class="flex-1 min-w-0 flex flex-col justify-center">
                                    <h4 class="font-bold text-xs sm:text-base text-slate-800 leading-tight truncate">${namaProgram}</h4>
                                    <p class="text-[11px] sm:text-xs text-slate-500 mt-1 sm:mt-1.5 leading-relaxed line-clamp-2">${prog.description ?? 'Tidak ada rincian deskripsi agenda kerja.'}</p>
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