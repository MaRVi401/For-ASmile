@extends('admin.layouts.app')

@section('title', 'Edit Catatan Penyaluran')

@section('content')
    <div class="space-y-6 max-w-5xl mx-auto">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Edit Catatan Penyaluran</h2>
                <p class="text-slate-500 text-sm mt-1">Lakukan koreksi teks deskripsi, foto dokumentasi, atau pembaruan info kontak penerima santunan.</p>
            </div>
            <a href="{{ route('admin.distributions.index') }}"
                class="flex items-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-medium transition duration-200 cursor-pointer">
                <i class="ti ti-arrow-left text-lg"></i> Kembali
            </a>
        </div>

        @if (session('error'))
            <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl flex items-center gap-3 text-sm font-semibold">
                <i class="ti ti-alert-triangle text-xl text-red-500"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        <!-- 1. MODIFIKASI: Ditambahkan enctype="multipart/form-data" -->
        <form action="{{ route('admin.distributions.update', $distribution->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">

                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4">
                    <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                        <i class="ti ti-lock text-base"></i> 1. Info Finansial & Dokumentasi
                    </h3>

                    <div>
                        <label class="block text-sm font-semibold text-slate-400 mb-1.5">Kampanye Terkait</label>
                        <input type="text" value="{{ $distribution->campaign->title }}" disabled
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-100 text-slate-400 text-sm font-medium outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-400 mb-1.5">Nominal Santunan Terdata</label>
                        <div class="relative flex items-center">
                            <span class="absolute left-4 text-sm font-bold text-slate-400">Rp</span>
                            <input type="text"
                                value="{{ number_format($distribution->amount_distributed, 0, ',', '.') }}" disabled
                                class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-100 text-slate-400 text-sm outline-none font-bold">
                        </div>
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-semibold text-slate-700 mb-1.5">Ubah Catatan Penyaluran</label>
                        <textarea name="notes" id="notes" rows="3" placeholder="Keterangan tambahan..."
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 text-sm focus:border-blue-500 focus:bg-white focus:ring-1 focus:ring-blue-500 outline-hidden transition">{{ old('notes', $distribution->notes) }}</textarea>
                        @error('notes')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 2. MODIFIKASI: Input & Preview Foto Bukti Dokumentasi -->
                    <div>
                        <label for="documentation_image" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Perbarui Foto Bukti Dokumentasi
                        </label>

                        @if ($distribution->documentation_image)
                            <div class="mb-3 relative group w-fit">
                                <img src="{{ asset('storage/' . $distribution->documentation_image) }}" 
                                     alt="Bukti Dokumentasi" 
                                     class="w-32 h-32 object-cover rounded-xl border border-slate-200 shadow-xs">
                                <a href="{{ asset('storage/' . $distribution->documentation_image) }}" target="_blank"
                                   class="absolute inset-0 bg-slate-900/40 text-white rounded-xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition text-xs font-semibold gap-1">
                                    <i class="ti ti-eye"></i> Lihat Foto
                                </a>
                            </div>
                        @endif

                        <input type="file" name="documentation_image" id="documentation_image" accept="image/*"
                            class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 text-sm focus:border-blue-500 focus:bg-white focus:ring-1 focus:ring-blue-500 outline-hidden transition file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="text-xs text-slate-400 mt-1">Kosongkan jika tidak ingin mengubah foto. Format: JPG, JPEG, PNG, WEBP (Maks. 2MB)</p>
                        @error('documentation_image')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4">
                    <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                        <i class="ti ti-user-edit text-base"></i> 2. Koreksi Informasi Penerima
                    </h3>

                    <div>
                        <label class="block text-sm font-semibold text-slate-400 mb-1.5">Nama Lengkap</label>
                        <input type="text" value="{{ $distribution->beneficiary->name }}" disabled
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-100 text-slate-400 text-sm font-bold outline-none">
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-semibold text-slate-700 mb-1.5">Perbarui Nomor HP / WhatsApp</label>
                        <input type="text" name="phone" id="phone"
                            value="{{ old('phone', $distribution->beneficiary->phone) }}" placeholder="Contoh: 08123456789"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')" inputmode="numeric"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 text-sm focus:border-blue-500 focus:bg-white focus:ring-1 focus:ring-blue-500 outline-hidden transition">
                        @error('phone')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-semibold text-slate-700 mb-1.5">Perbarui Alamat Rumah</label>
                        <textarea name="address" id="address" rows="3" placeholder="Alamat tinggal penerima..."
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 text-sm focus:border-blue-500 focus:bg-white focus:ring-1 focus:ring-blue-500 outline-hidden transition">{{ old('address', $distribution->beneficiary->address) }}</textarea>
                        @error('address')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-slate-200">
                <a href="{{ route('admin.distributions.index') }}"
                    class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-medium text-sm transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-semibold text-sm shadow-md shadow-amber-100 transition cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection