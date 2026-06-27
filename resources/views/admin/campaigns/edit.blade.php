@extends('admin.layouts.app')

@section('title', 'Edit Kampanye')

@section('content')
    <div class="space-y-6 max-w-3xl mx-auto">
        <!-- Tombol Kembali & Judul -->
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.campaigns.index') }}"
                class="p-2.5 bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 rounded-xl transition shadow-xs cursor-pointer"
                title="Kembali">
                <i class="ti ti-arrow-left text-lg"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Edit Kampanye Bulanan</h2>
                <p class="text-slate-500 text-sm mt-1">Perbarui data target, deskripsi, atau status publikasi untuk periode ini.</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <form action="{{ route('admin.campaigns.update', $campaign->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Baris 0: Gambar / Poster Kampanye dengan Live Preview -->
                <div>
                    <label for="image" class="block text-slate-700 text-sm font-semibold mb-2">Gambar / Poster Kampanye</label>
                    <div class="flex flex-col md:flex-row gap-4 items-start">
                        <!-- Kotak Preview Gambar (Menampilkan gambar lama jika ada) -->
                        <div id="image-preview-container" class="{{ $campaign->image_url ? '' : 'hidden' }} w-full md:w-48 h-48 border-2 border-dashed border-slate-200 rounded-2xl overflow-hidden bg-slate-50 flex items-center justify-center shrink-0">
                            <img id="image-preview" src="{{ $campaign->image_url ? asset('storage/' . $campaign->image_url) : '#' }}" alt="Preview" class="w-full h-full object-cover">
                        </div>
                        <div class="w-full">
                            <input type="file" name="image" id="image" accept="image/*"
                                class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 file:cursor-pointer">
                            <p class="text-xs text-slate-400 mt-1">Format: JPEG, PNG, JPG, WEBP (Max 2MB). Kosongkan jika tidak ingin mengubah gambar.</p>
                        </div>
                    </div>
                    @error('image')
                        <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Baris 1: Judul Kampanye -->
                <div>
                    <label for="title" class="block text-slate-700 text-sm font-semibold mb-2">Nama / Judul Kampanye</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $campaign->title) }}"
                        class="w-full px-4 py-2.5 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition @error('title') border-red-500 @else border-slate-300 @enderror"
                        required placeholder="Contoh: Kampanye Kebaikan Bulan Mei 2026">
                    @error('title')
                        <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Baris 2: Grid Bulan dan Target Dana -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Target Periode Bulan -->
                    <div>
                        <label for="month" class="block text-slate-700 text-sm font-semibold mb-2">Target Bulan Periode</label>
                        <!-- Atribut min dikunci ke bulan terkecil antara bulan sekarang ATAU bulan bawaan data yang sedang diedit -->
                        @php
                            $minMonth = date('Y-m') < $campaign->month ? date('Y-m') : $campaign->month;
                        @endphp
                        <input type="month" name="month" id="month" value="{{ old('month', $campaign->month) }}"
                            min="{{ $minMonth }}"
                            class="w-full px-4 py-2.5 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition @error('month') border-red-500 @else border-slate-300 @enderror"
                            required>
                        @error('month')
                            <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Target Nominal Dana dengan Masking Format Ribuan -->
                    <div>
                        <label for="target_amount_display" class="block text-slate-700 text-sm font-semibold mb-2">Target Donasi Bulanan (Rupiah)</label>
                        <div class="relative flex items-center">
                            <span class="absolute left-4 text-slate-400 font-semibold text-sm">Rp</span>
                            
                            <!-- Input Tampilan Visual Berformat Titik -->
                            <input type="text" id="target_amount_display" 
                                class="w-full pl-11 pr-4 py-2.5 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition @error('target_amount') border-red-500 @else border-slate-300 @enderror"
                                placeholder="Contoh: 50.000.000" required>
                            
                            <!-- Input Utama Tersembunyi (Hanya Angka Murni) yang Masuk ke Database -->
                            <input type="hidden" name="target_amount" id="target_amount" value="{{ old('target_amount', (int) $campaign->target_amount) }}">
                        </div>
                        @error('target_amount')
                            <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Baris 3: Status Kampanye (Hanya Draft dan Active) -->
                <div>
                    <label for="status" class="block text-slate-700 text-sm font-semibold mb-2">Status Publikasi</label>
                    <select name="status" id="status"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        <option value="draft" {{ old('status', $campaign->status) == 'draft' ? 'selected' : '' }}>Draft (Sembunyikan dulu)</option>
                        <option value="active" {{ old('status', $campaign->status) == 'active' ? 'selected' : '' }}>Active (Aktif / Buka Donasi)</option>
                    </select>
                    @error('status')
                        <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Baris 4: Deskripsi -->
                <div>
                    <label for="description" class="block text-slate-700 text-sm font-semibold mb-2">Deskripsi Kampanye (Opsional)</label>
                    <textarea name="description" id="description" rows="4"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        placeholder="Tulis ringkasan mengenai gambaran umum atau urgensi bantuan kampanye bulan ini...">{{ old('description', $campaign->description) }}</textarea>
                    @error('description')
                        <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Batasan Tombol Aksi -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('admin.campaigns.index') }}"
                        class="px-5 py-2.5 border border-slate-200 hover:bg-slate-50 text-slate-600 rounded-xl font-medium transition cursor-pointer">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium shadow-md shadow-blue-200 transition duration-200 cursor-pointer">
                        Perbarui Kampanye
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. LOGIC PREVIEW GAMBAR BARU
            const imageInput = document.getElementById('image');
            const previewContainer = document.getElementById('image-preview-container');
            const previewImage = document.getElementById('image-preview');

            imageInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.setAttribute('src', e.target.result);
                        previewContainer.classList.remove('hidden');
                    }
                    reader.readAsDataURL(file);
                }
            });

            // 2. LOGIC NUMBER FORMATTING (RUPIAH)
            const displayInput = document.getElementById('target_amount_display');
            const hiddenInput = document.getElementById('target_amount');

            function formatNumber(value) {
                return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            displayInput.addEventListener('input', function(e) {
                let rawValue = this.value.replace(/\D/g, "");
                hiddenInput.value = rawValue;
                this.value = formatNumber(rawValue);
            });

            // Isi format titik secara otomatis saat halaman pertama kali dimuat (Edit Mode)
            if (hiddenInput.value) {
                displayInput.value = formatNumber(hiddenInput.value);
            }
        });
    </script>
@endsection