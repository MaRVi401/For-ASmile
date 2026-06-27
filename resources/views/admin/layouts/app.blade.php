<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - FAS</title>
    <link rel="icon" href="{{ asset('assets/image/fas-logo.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-slate-50 text-slate-800 font-sans antialiased" x-data="{ mobileSidebarOpen: false }">

    <div class="flex h-screen overflow-hidden relative">

        <div x-show="mobileSidebarOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click="mobileSidebarOpen = false"
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-40 lg:hidden" style="display: none;">
        </div>

        <aside :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed inset-y-0 left-0 w-64 bg-slate-900 text-white flex flex-col h-full shadow-xl z-50 lg:static lg:translate-x-0 transition-transform duration-300 ease-in-out">

            <div class="p-6 border-b border-slate-800 flex items-center justify-between lg:justify-center gap-3">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('assets/image/fas-logo.png') }}" alt="Logo E-Donasi"
                        class="h-10 w-auto object-contain">
                    <span class="text-lg font-bold tracking-wide text-white">For A Smile</span>
                </div>
                <button @click="mobileSidebarOpen = false"
                    class="lg:hidden text-slate-400 hover:text-white p-1 rounded-lg hover:bg-slate-800 cursor-pointer">
                    <i class="ti ti-x text-xl"></i>
                </button>
            </div>

            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-800 transition font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/30' : 'text-slate-400 hover:text-slate-200' }}">
                    <i class="ti ti-layout-dashboard text-xl"></i> Dashboard
                </a>

                <a href="{{ route('admin.campaigns.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-800 transition font-medium {{ request()->routeIs('admin.campaigns.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/30' : 'text-slate-400 hover:text-slate-200' }}">
                    <i class="ti ti-calendar-event text-xl"></i> Kelola Kampanye
                </a>

                <a href="{{ route('admin.programs.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-800 transition font-medium {{ request()->routeIs('admin.programs.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/30' : 'text-slate-400 hover:text-slate-200' }}">
                    <i class="ti ti-rocket text-xl"></i> Kelola Program
                </a>

                <a href="{{ route('admin.transactions.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-800 transition font-medium {{ request()->routeIs('admin.transactions.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/30' : 'text-slate-400 hover:text-slate-200' }}">
                    <i class="ti ti-report-money text-xl"></i> Laporan Transaksi
                </a>
            </nav>

            <div class="p-4 border-t border-slate-800">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-red-600/10 hover:bg-red-600 text-red-400 hover:text-white rounded-xl transition font-medium text-sm cursor-pointer group">
                        <i
                            class="ti ti-logout text-base group-hover:translate-x-0.5 transition-transform duration-200"></i>
                        Keluar / Logout
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col h-full overflow-hidden">

            <header
                class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-6 lg:px-8 z-10 shadow-xs">

                <div class="flex items-center gap-3">
                    <button @click="mobileSidebarOpen = true"
                        class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100 cursor-pointer transition-colors">
                        <i class="ti ti-menu-2 text-2xl"></i>
                    </button>

                    <div class="text-sm font-medium text-slate-500 hidden sm:flex items-center gap-2">
                        <i class="ti ti-clock-hour-4 text-slate-400 text-base"></i>
                        Hari ini: <span
                            class="text-slate-800 font-semibold">{{ now()->translatedFormat('l, d F Y') }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-sm font-semibold text-slate-800">{{ Auth::user()->name ?? 'Administrator' }}</p>
                        <p class="text-xs text-green-600 font-medium flex items-center justify-end gap-1">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span> Online
                        </p>
                    </div>
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-slate-100 to-slate-200 rounded-full flex items-center justify-center font-bold text-slate-600 border border-slate-300 shadow-xs select-none">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                    </div>
                </div>
            </header>

            <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto">

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        @if (session('success'))
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: "{{ session('success') }}",
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                customClass: {
                                    popup: 'rounded-2xl'
                                }
                            });
                        @endif

                        @if (session('error'))
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kendala!',
                                text: "{{ session('error') }}",
                                confirmButtonText: 'Mengerti',
                                confirmButtonColor: '#3b82f6',
                                customClass: {
                                    popup: 'rounded-2xl',
                                    confirmButton: 'rounded-xl px-5 py-2.5 font-medium'
                                }
                            });
                        @endif

                        @if ($errors->any())
                            let errorMessages = '';
                            @foreach ($errors->all() as $error)
                                errorMessages += '• {{ $error }}\n';
                            @endforeach

                            Swal.fire({
                                icon: 'warning',
                                title: 'Periksa Isian Form!',
                                text: errorMessages,
                                confirmButtonText: 'Perbaiki',
                                confirmButtonColor: '#f59e0b',
                                customClass: {
                                    popup: 'rounded-2xl',
                                    confirmButton: 'rounded-xl px-5 py-2.5 font-medium'
                                }
                            });
                        @endif
                    });
                </script>

                @yield('content')
            </main>

        </div>
    </div>
    @stack('scripts')
</body>

</html>
