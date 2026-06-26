<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" 
        data-client-key="{{ config('midtrans.client_key') }}"></script>

<div class="max-w-md mx-auto bg-white p-6 rounded-2xl shadow-sm text-center">
    <h2 class="text-xl font-bold mb-2">Konfirmasi Donasi</h2>
    <p class="text-slate-500 mb-4">Anda akan berdonasi untuk program: <strong>{{ $campaign->title }}</strong></p>
    <div class="text-2xl font-black text-blue-600 mb-6">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</div>

    <button id="pay-button" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 rounded-xl transition cursor-pointer">
        Bayar Sekarang
    </button>
</div>

<script type="text/javascript">
    var payButton = document.getElementById('pay-button');
    payButton.addEventListener('click', function () {
        // Trigger snap popup menggunakan token dari controller
        window.snap.pay('{{ $snapToken }}', {
            onSuccess: function(result){
                /* Hubungkan ke halaman sukses atau reload */
                window.location.href = "/dashboard";
            },
            onPending: function(result){
                /* Pembayaran tertunda (menunggu transfer) */
                window.location.reload();
            },
            onError: function(result){
                /* Pembayaran gagal */
                alert("Pembayaran gagal, silahkan coba lagi.");
            },
            onClose: function(){
                /* Pembayaran dibatalkan di tengah jalan oleh user */
                alert('Anda menutup halaman pembayaran sebelum selesai.');
            }
        });
    });
</script>