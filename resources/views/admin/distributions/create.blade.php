@extends('admin.layouts.app')

@section('title', 'Catat Penyaluran')

@section('content')
    <div class="space-y-6 max-w-5xl mx-auto">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Catat Penyaluran Donasi</h2>
                <p class="text-slate-500 text-sm mt-1">Dokumentasikan pemberian bantuan fisik / tunai ke penerima manfaat.
                </p>
            </div>
            <a href="{{ route('admin.distributions.index') }}"
                class="flex items-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-medium transition duration-200 cursor-pointer">
                <i class="ti ti-arrow-left text-lg"></i> Kembali
            </a>
        </div>

        @if (session('error'))
            <div
                class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl flex items-center gap-3 text-sm font-semibold">
                <i class="ti ti-alert-triangle text-xl text-red-500"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        <form action="{{ route('admin.distributions.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">

                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4">
                    <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                        <i class="ti ti-coin text-base"></i> 1. Alokasi Kampanye Bulanan
                    </h3>

                    <div>
                        <label for="campaign_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Pilih Kampanye
                            <span class="text-red-500">*</span></label>
                        <select name="campaign_id" id="campaign_id" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 text-sm focus:border-blue-500 focus:bg-white focus:ring-1 focus:ring-blue-500 outline-hidden transition">
                            <option value="" selected disabled>-- Pilih Wadah Kampanye --</option>
                            @foreach ($campaigns as $campaign)
                                <option value="{{ $campaign->id }}"
                                    {{ old('campaign_id') == $campaign->id ? 'selected' : '' }}>
                                    {{ $campaign->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('campaign_id')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div>
                            <label for="amount_distributed_display"
                                class="block text-sm font-semibold text-slate-700 mb-1.5">Nominal Santunan (Rp) <span
                                    class="text-red-500">*</span></label>
                            <div class="relative flex items-center">
                                <span class="absolute left-4 text-sm font-bold text-slate-400">Rp</span>

                                <input type="text" id="amount_distributed_display" required placeholder="0"
                                    class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 text-sm focus:border-blue-500 focus:bg-white focus:ring-1 focus:ring-blue-500 outline-hidden transition font-semibold">

                                <input type="hidden" name="amount_distributed" id="amount_distributed"
                                    value="{{ old('amount_distributed') }}">
                            </div>
                            @error('amount_distributed')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        @error('amount_distributed')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-semibold text-slate-700 mb-1.5">Keterangan
                            Penyaluran</label>
                        <textarea name="notes" id="notes" rows="4"
                            placeholder="Contoh: Pembagian paket sembako dan uang saku tunai..."
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 text-sm focus:border-blue-500 focus:bg-white focus:ring-1 focus:ring-blue-500 outline-hidden transition">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4">
                    <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                        <i class="ti ti-user text-base"></i> 2. Profil Penerima Santunan
                    </h3>

                    <div>
                        <label for="beneficiary_select" class="block text-sm font-semibold text-slate-700 mb-1.5">Metode
                            Input Penerima</label>
                        <select id="beneficiary_select" name="beneficiary_id"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-800 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-hidden transition font-medium">
                            <option value="">+ Daftarkan Penerima Baru</option>
                            @foreach ($beneficiaries as $b)
                                <option value="{{ $b->id }}"
                                    {{ old('beneficiary_id') == $b->id ? 'selected' : '' }}
                                    data-address="{{ $b->address }}" data-phone="{{ $b->phone }}">
                                    {{ $b->name }} (Sudah {{ $b->distributions_count }}x Menerima)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="manual_fields" class="space-y-4 pt-2 border-t border-slate-100">
                        <div>
                            <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}"
                                placeholder="Masukkan nama penerima"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 text-sm focus:border-blue-500 focus:bg-white focus:ring-1 focus:ring-blue-500 outline-hidden transition">
                            @error('name')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor HP /
                                WhatsApp</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')" inputmode="numeric"
                                placeholder="Contoh: 08123456789"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 text-sm focus:border-blue-500 focus:bg-white focus:ring-1 focus:ring-blue-500 outline-hidden transition">
                            @error('phone')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat
                                Lengkap</label>
                            <textarea name="address" id="address" rows="2" placeholder="Alamat tinggal saat ini..."
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 text-sm focus:border-blue-500 focus:bg-white focus:ring-1 focus:ring-blue-500 outline-hidden transition">{{ old('address') }}</textarea>
                            @error('address')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-slate-200">
                <button type="reset"
                    class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-medium text-sm transition cursor-pointer">
                    Reset Form
                </button>
                <button type="submit"
                    class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-sm shadow-md shadow-blue-100 transition cursor-pointer">
                    Simpan Log Penyaluran
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectElement = document.getElementById('beneficiary_select');
            const inputName = document.getElementById('name');
            const inputPhone = document.getElementById('phone');
            const inputAddress = document.getElementById('address');

            function handleFormState() {
                const activeOption = selectElement.options[selectElement.selectedIndex];

                if (selectElement.value !== "") {
                    // Isi data lama & Kunci kolom agar tidak merusak master data
                    inputName.value = activeOption.text.split('(')[0].trim();
                    inputPhone.value = activeOption.getAttribute('data-phone') || '';
                    inputAddress.value = activeOption.getAttribute('data-address') || '';

                    inputName.readOnly = true;
                    inputPhone.readOnly = true;
                    inputAddress.readOnly = true;
                    inputName.required = false;

                    // Tambahkan style visual disabled khas Tailwind
                    inputName.classList.add('bg-slate-100', 'text-slate-400');
                    inputPhone.classList.add('bg-slate-100', 'text-slate-400');
                    inputAddress.classList.add('bg-slate-100', 'text-slate-400');
                } else {
                    // Reset field dan buka akses untuk entri baru
                    inputName.value = '';
                    inputPhone.value = '';
                    inputAddress.value = '';

                    inputName.readOnly = false;
                    inputPhone.readOnly = false;
                    inputAddress.readOnly = false;
                    inputName.required = true;

                    inputName.classList.remove('bg-slate-100', 'text-slate-400');
                    inputPhone.classList.remove('bg-slate-100', 'text-slate-400');
                    inputAddress.classList.remove('bg-slate-100', 'text-slate-400');
                }
            }

            selectElement.addEventListener('change', handleFormState);
            handleFormState();

            // =========================================================================
            // --- KODE BARU: LOGIKA FORMAT RUPIAH REAL-TIME ---
            // =========================================================================
            const displayInput = document.getElementById('amount_distributed_display');
            const hiddenInput = document.getElementById('amount_distributed');

            // Fungsi mengubah angka menjadi format ribuan (Contoh: 50000 -> 50.000)
            function formatRupiah(value) {
                if (!value) return '';
                const cleanNumber = value.replace(/\D/g, ''); // Buang semua karakter selain angka
                return new Intl.NumberFormat('id-ID').format(cleanNumber);
            }

            // Event saat admin mengetik nominal
            displayInput.addEventListener('input', function(e) {
                const rawValue = e.target.value;
                const formatted = formatRupiah(rawValue);

                // Tampilkan teks berformat ke admin
                e.target.value = formatted;

                // Simpan angka bersih tanpa titik ke hidden input untuk dikirim ke database
                hiddenInput.value = rawValue.replace(/\D/g, '');
            });

            // Jalankan saat halaman pertama kali dibuka (mengakomodasi nilai dari old() jika validasi gagal)
            if (hiddenInput.value) {
                displayInput.value = formatRupiah(hiddenInput.value);
            }
        });
    </script>
@endpush
