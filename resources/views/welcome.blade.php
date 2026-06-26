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
                    <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-blue-600 bg-blue-50 px-4 py-2 rounded-xl hover:bg-blue-100 transition">
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

        <!-- Grid Cards Kampanye (Mobile: 1 kolom, Tablet: 2 kolom, Desktop: 3 kolom) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            @forelse($campaigns as $campaign)
                <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden flex flex-col hover:shadow-md transition duration-300">
                    <!-- Thumbnail Dummy / Placeholder -->
                    <div class="h-48 bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center relative p-6">
                        <i class="ti ti-heart-handshake text-white text-5xl opacity-80"></i>
                        <span class="absolute bottom-3 left-3 bg-slate-900/40 backdrop-blur-xs text-white text-xs px-2.5 py-1 rounded-md font-medium tracking-wide">
                            {{ $campaign->program->title ?? 'Umum' }}
                        </span>
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
                        </div>

                        <!-- Button Action -->
                        <div class="pt-4 border-t border-slate-100">
                            <a href="{{ route('donation.create', $campaign->id) }}" 
                               class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-xl transition cursor-pointer text-sm shadow-xs shadow-blue-600/10">
                                <i class="ti ti-heart text-base"></i> Donasi Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white border border-dashed border-slate-300 rounded-2xl p-12 text-center text-slate-500">
                    <i class="ti ti-folder-off text-4xl mb-2 text-slate-300 block"></i>
                    Belum ada data kampanye. Silakan tambahkan lewat admin panel terlebih dahulu.
                </div>
            @endforelse
        </div>
    </main>

</body>
</html>