<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selesaikan Pembayaran - For A Smile</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    
    <!-- SDK Midtrans Snap (Sandbox Environment) -->
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" 
            data-client-key="{{ config('midtrans.client_key') }}"></script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased font-sans flex flex-col min-h-screen justify-center p-4">

    <div class="max-w-md w-full mx-auto bg-white border border-slate-200 rounded-3xl shadow-sm text-center overflow-hidden">
        <div class="p-6 sm:p-8">
            <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl">
                <i class="ti ti-wallet"></i>
            </div>

            <h2 class="text-xl font-black text-slate-900 tracking-tight mb-1">Konfirmasi Pembayaran</h2>
            <p class="text-sm text-slate-500 mb-4">Terima kasih atas niat baik Anda untuk mendukung kampanye <span class="font-semibold text-slate-700">{{ $campaign->title }}</span></p>
            
            <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 mb-6">
                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Donasi</span>
                <span class="text-2xl font-black text-blue-600">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
                <span class="block text-[11px] text-slate-400 mt-1 font-mono">ID: {{ $transaction->order_id }}</span>
            </div>

            <button id="pay-button" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-4 rounded-xl transition cursor-pointer text-sm shadow-xs shadow-blue-600/10 flex items-center justify-center gap-2">
                Bayar Sekarang <i class="ti ti-external-link text-base"></i>
            </button>
        </div>
    </div>

    <script type="text/javascript">
        var payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function () {
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result){
                    alert('Pembayaran Berhasil! Terima kasih atas bantuan Anda.');
                    window.location.href = "/";
                },
                onPending: function(result){
                    alert('Menunggu pembayaran Anda. Silakan selesaikan transaksi sesuai instruksi.');
                    window.location.href = "/";
                },
                onError: function(result){
                    alert('Terjadi kesalahan atau pembayaran gagal.');
                },
                onClose: function(){
                    alert('Anda menutup jendela pembayaran sebelum transaksi selesai.');
                }
            });
        });
    </script>
</body>
</html>