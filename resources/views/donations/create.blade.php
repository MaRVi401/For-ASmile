<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masukkan Nominal Donasi - For A Smile</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
</head>
<body class="bg-slate-50 text-slate-800 antialiased font-sans flex flex-col min-h-screen justify-center p-4">

    <div class="max-w-md w-full mx-auto bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">
        <div class="p-6 sm:p-8">
            <a href="/" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 mb-6 transition">
                <i class="ti ti-arrow-left"></i> Kembali ke Katalog
            </a>

            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight mb-1">Formulir Donasi</h2>
            <p class="text-sm text-slate-500 mb-6">Kampanye: <span class="font-bold text-slate-700">{{ $campaign->title }}</span></p>

            <form action="{{ route('donation.store') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="campaign_id" value="{{ $campaign->id }}">

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Jumlah Donasi (Rupiah)</label>
                    <div class="relative rounded-xl shadow-xs">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 font-bold text-sm">
                            Rp
                        </div>
                        <input type="number" name="amount" id="amount" required min="10000" placeholder="10000"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3.5 pl-11 pr-4 font-bold text-slate-800 placeholder-slate-400 focus:outline-hidden focus:border-blue-500 focus:bg-white transition text-base">
                    </div>
                    <p class="text-slate-400 text-xs mt-2">Minimal donasi Rp 10.000</p>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-4 rounded-xl transition cursor-pointer text-sm shadow-xs shadow-blue-600/10 flex items-center justify-center gap-2">
                    Lanjutkan ke Pembayaran <i class="ti ti-arrow-right text-base"></i>
                </button>
            </form>
        </div>
    </div>

</body>
</html>