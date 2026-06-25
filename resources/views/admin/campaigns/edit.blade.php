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
                <p class="text-slate-500 text-sm mt-1">Perbarui data target, deskripsi, atau status publikasi untuk periode
                    ini.</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <form action="{{ route('admin.campaigns.update', $campaign->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Baris 0: Gambar / Poster Kampanye -->
                <div>
                    <label class="block text-slate-700 text-sm font-semibold mb-2">Gambar / Poster Kampanye</label>
                    @if ($campaign->image_url)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $campaign->image_url) }}" alt="Preview Poster"
                                class="h-32 w-auto object-cover rounded-xl border border-slate-200 shadow-xs">
                        </div>
                    @endif
                    <input type="file" name="image" id="image" accept="image/*"
                        class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    <p class="text-xs text-slate-400 mt-1">Kosongkan jika tidak ingin mengubah gambar.</p>
                </div>

                <!-- Baris 1: Judul Kampanye -->
                <div>
                    <label for="title" class="block text-slate-700 text-sm font-semibold mb-2">Nama / Judul
                        Kampanye</label>
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
                        <label for="month" class="block text-slate-700 text-sm font-semibold mb-2">Target Bulan
                            Periode</label>
                        <input type="month" name="month" id="month" value="{{ old('month', $campaign->month) }}"
                            class="w-full px-4 py-2.5 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition @error('month') border-red-500 @else border-slate-300 @enderror"
                            required>
                        @error('month')
                            <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Target Nominal Dana -->
                    <div>
                        <label for="target_amount" class="block text-slate-700 text-sm font-semibold mb-2">Target Donasi
                            Bulanan (Rupiah)</label>
                        <div class="relative flex items-center">
                            <span class="absolute left-4 text-slate-400 font-semibold text-sm">Rp</span>
                            <input type="number" name="target_amount" id="target_amount"
                                value="{{ old('target_amount', (int) $campaign->target_amount) }}" min="0"
                                step="1000"
                                class="w-full pl-11 pr-4 py-2.5 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition @error('target_amount') border-red-500 @else border-slate-300 @enderror"
                                required placeholder="Contoh: 50000000">
                        </div>
                        @error('target_amount')
                            <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Baris 3: Status Kampanye -->
                <div>
                    <label for="status" class="block text-slate-700 text-sm font-semibold mb-2">Status Publikasi</label>
                    <select name="status" id="status"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        <option value="draft" {{ old('status', $campaign->status) == 'draft' ? 'selected' : '' }}>Draft
                            (Sembunyikan dulu)</option>
                        <option value="upcoming" {{ old('status', $campaign->status) == 'upcoming' ? 'selected' : '' }}>
                            Upcoming (Akan Datang / H-1 atau H-2 Bulan)</option>
                        <option value="active" {{ old('status', $campaign->status) == 'active' ? 'selected' : '' }}>Active
                            (Aktif / Buka Donasi)</option>
                        <option value="completed" {{ old('status', $campaign->status) == 'completed' ? 'selected' : '' }}>
                            Completed (Selesai)</option>
                    </select>
                    @error('status')
                        <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Baris 4: Deskripsi -->
                <div>
                    <label for="description" class="block text-slate-700 text-sm font-semibold mb-2">Deskripsi Kampanye
                        (Opsional)</label>
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
@endsection
