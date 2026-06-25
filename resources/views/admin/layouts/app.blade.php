<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - E-Donasi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-slate-50 text-slate-800 font-sans antialiased">

    <div class="flex h-screen overflow-hidden">

        <aside class="w-64 bg-slate-900 text-white flex flex-col h-full shadow-xl z-20">
            <div class="p-6 border-b border-slate-800 flex items-center justify-center gap-3">
                <img src="{{ asset('assets/image/fas-logo.png') }}" alt="Logo E-Donasi" class="h-10 w-auto object-contain">
                <span class="text-lg font-bold tracking-wide text-white">For A Smile</span>
            </div>

            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-800 transition font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/30' : 'text-slate-400 hover:text-slate-200' }}">
                    <i class="ti ti-layout-dashboard text-xl"></i> Dashboard
                </a>

                <a href="#"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-800 transition font-medium text-slate-400 hover:text-slate-200">
                    <i class="ti ti-calendar-event text-xl"></i> Kelola Kampanye
                </a>

                <a href="#"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-800 transition font-medium text-slate-400 hover:text-slate-200">
                    <i class="ti ti-rocket text-xl"></i> Kelola Program
                </a>

                <a href="#"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-800 transition font-medium text-slate-400 hover:text-slate-200">
                    <i class="ti ti-report-money text-xl"></i> Laporan Transaksi
                </a>
            </nav>

            <div class="p-4 border-t border-slate-800">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-red-600/10 hover:bg-red-600 text-red-400 hover:text-white rounded-xl transition font-medium text-sm cursor-pointer group">
                        <i class="ti ti-logout text-base group-hover:translate-x-0.5 transition-transform duration-200"></i> Keluar / Logout
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col h-full overflow-hidden">

            <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8 z-10 shadow-xs">
                <div class="text-sm font-medium text-slate-500 flex items-center gap-2">
                    <i class="ti ti-clock-hour-4 text-slate-400 text-base"></i>
                    Hari ini: <span class="text-slate-800 font-semibold">{{ now()->translatedFormat('l, d F Y') }}</span>
                </div>

                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-sm font-semibold text-slate-800">{{ Auth::user()->name ?? 'Administrator' }}</p>
                        <p class="text-xs text-green-600 font-medium flex items-center justify-end gap-1">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span> Online
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-linear-to-br from-slate-100 to-slate-200 rounded-full flex items-center justify-center font-bold text-slate-600 border border-slate-300 shadow-xs select-none">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                    </div>
                </div>
            </header>

            <main class="flex-1 p-8 overflow-y-auto">
                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-r-xl shadow-xs text-sm flex items-center gap-2">
                        <i class="ti ti-circle-check text-lg text-green-600"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>

        </div>
    </div>

</body>

</html>