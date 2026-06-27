<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masukkan Nominal Donasi - For A Smile</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">

    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
</head>

<body class="bg-slate-50 text-slate-800 antialiased font-sans flex flex-col min-h-screen justify-center p-4">

    <div class="max-w-md w-full mx-auto bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">
        <div class="p-6 sm:p-8">
            <a href="/"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 mb-6 transition">
                <i class="ti ti-arrow-left"></i> Kembali ke Katalog
            </a>

            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight mb-1">Formulir Donasi</h2>
            <p class="text-sm text-slate-500 mb-6">Kampanye: <span class="font-bold text-slate-700"
                    id="campaign-title">{{ $campaign->title }}</span></p>

            <form id="donation-form" class="space-y-5">
                @csrf
                <input type="hidden" name="campaign_id" id="campaign_id" value="{{ $campaign->id }}">

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Jumlah Donasi
                        (Rupiah)</label>
                    <div class="relative rounded-xl shadow-xs">
                        <div
                            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 font-bold text-sm">
                            Rp
                        </div>
                        <input type="number" name="amount" id="amount" required min="10000" placeholder="10000"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3.5 pl-11 pr-4 font-bold text-slate-800 placeholder-slate-400 focus:outline-hidden focus:border-blue-500 focus:bg-white transition text-base">
                    </div>
                    <p class="text-slate-400 text-xs mt-2">Minimal donasi Rp 10.000</p>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-4 rounded-xl transition cursor-pointer text-sm shadow-xs shadow-blue-600/10 flex items-center justify-center gap-2">
                    Lanjutkan <i class="ti ti-arrow-right text-base"></i>
                </button>
            </form>
        </div>
    </div>

    <div id="confirmation-modal"
        class="hidden fixed inset-0 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4 z-50">
        <div class="bg-white max-w-sm w-full rounded-2xl p-6 shadow-xl border border-slate-100">
            <h3 class="text-lg font-bold text-slate-900 mb-2">Konfirmasi Donasi</h3>
            <p class="text-sm text-slate-500 mb-4">Apakah Anda yakin ingin berdonasi untuk kampanye <span
                    class="font-semibold text-slate-700">{{ $campaign->title }}</span> sebesar:</p>

            <div class="bg-slate-50 rounded-xl p-4 mb-6 text-center">
                <span class="text-2xl font-black text-blue-600" id="modal-amount-display">Rp 0</span>
            </div>

            <div class="flex gap-3">
                <button id="btn-cancel"
                    class="flex-1 py-3 text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition cursor-pointer">
                    Batal
                </button>
                <button id="btn-confirm"
                    class="flex-1 py-3 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition cursor-pointer flex items-center justify-center gap-2">
                    <span id="btn-text">Ya, Donasi</span>
                    <svg id="loading-spinner" class="hidden animate-spin h-4 w-4 text-white" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('donation-form');
        const modal = document.getElementById('confirmation-modal');
        const amountInput = document.getElementById('amount');
        const modalAmountDisplay = document.getElementById('modal-amount-display');
        const btnCancel = document.getElementById('btn-cancel');
        const btnConfirm = document.getElementById('btn-confirm');
        const btnText = document.getElementById('btn-text');
        const loadingSpinner = document.getElementById('loading-spinner');

        // 1. Tangani ketika tombol formulir utama di-klik
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const amount = parseInt(amountInput.value);
            if (isNaN(amount) || amount < 10000) {
                alert('Minimal donasi adalah Rp 10.000');
                return;
            }

            // Format angka ke Rupiah untuk tampilan modal
            modalAmountDisplay.innerText = 'Rp ' + amount.toLocaleString('id-ID');

            // Tampilkan Modal
            modal.classList.remove('hidden');
        });

        // 2. Tombol Batal di Modal
        btnCancel.addEventListener('click', function() {
            modal.classList.add('hidden');
        });

        // 3. Tombol Konfirmasi di Modal (Proses AJAX ke Server)
        btnConfirm.addEventListener('click', async function() {
            // Ubah button jadi loading state
            btnConfirm.disabled = true;
            btnCancel.disabled = true;
            btnText.innerText = 'Memproses...';
            loadingSpinner.classList.remove('hidden');

            const formData = new FormData();
            formData.append('campaign_id', document.getElementById('campaign_id').value);
            formData.append('amount', amountInput.value);
            formData.append('_token', '{{ csrf_token() }}' || document.querySelector('input[name="_token"]')
                .value);

            try {
                // Kirim request POST ke controller (DonationController@store)
                const response = await fetch("{{ route('donation.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Terjadi kesalahan pada server.');
                }

                // Sembunyikan Modal Konfirmasi
                modal.classList.add('hidden');

                // 4. OTOMATIS panggil popup Midtrans Snap menggunakan token dari server
                window.snap.pay(data.snapToken, {
                    onSuccess: function(result) {
                        // Redirect atau aksi ketika sukses bayar
                        window.location.href = `/`;
                    },
                    onPending: function(result) {
                        // Terjadi ketika user memilih instruksi pembayaran tapi belum transfer (misal Alfamart/VA)
                        window.location.href = `/`;
                    },
                    onError: function(result) {
                        alert("Pembayaran gagal!");
                        console.error(result);
                    },
                    onClose: function() {
                        alert('Anda menutup popup pembayaran sebelum menyelesaikan transaksi.');
                    }
                });

            } catch (error) {
                alert(error.message);
            } finally {
                // Kembalikan status tombol modal ke semula
                btnConfirm.disabled = false;
                btnCancel.disabled = false;
                btnText.innerText = 'Ya, Donasi';
                loadingSpinner.classList.add('hidden');
            }
        });
    </script>
</body>

</html>
