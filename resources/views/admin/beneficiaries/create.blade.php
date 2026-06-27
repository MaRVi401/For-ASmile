@extends('admin.layouts.app')

@section('title', 'Tambah Penerima Santunan')

@section('content')
    <div class="space-y-6 max-w-2xl mx-auto">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Tambah Penerima Santunan</h2>
                <p class="text-slate-500 text-sm mt-1">Daftarkan profil penerima manfaat baru secara manual ke dalam master
                    data database.</p>
            </div>
            <a href="{{ route('admin.beneficiaries.index') }}"
                class="flex items-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-medium transition duration-200 cursor-pointer">
                <i class="ti ti-arrow-left text-lg"></i> Kembali
            </a>
        </div>

        {{-- Notifikasi Error Back-end jika Ada --}}
        @if (session('error'))
            <div
                class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl flex items-center gap-3 text-sm font-semibold">
                <i class="ti ti-alert-triangle text-xl text-red-500"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
            <form action="{{ route('admin.beneficiaries.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap Penerima
                        <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        placeholder="Masukkan nama lengkap"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 text-sm focus:border-blue-500 focus:bg-white focus:ring-1 focus:ring-blue-500 outline-hidden transition font-semibold">
                    @error('name')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor HP /
                        WhatsApp</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                        placeholder="Contoh: 08123456789" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                        inputmode="numeric"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 text-sm focus:border-blue-500 focus:bg-white focus:ring-1 focus:ring-blue-500 outline-hidden transition">
                    @error('phone')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="address" class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat Lengkap
                        Rumah</label>
                    <textarea name="address" id="address" rows="4"
                        placeholder="Masukkan alamat domisili warga atau tempat tinggal saat ini..."
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 text-sm focus:border-blue-500 focus:bg-white focus:ring-1 focus:ring-blue-500 outline-hidden transition">{{ old('address') }}</textarea>
                    @error('address')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('admin.beneficiaries.index') }}"
                        class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-medium text-sm transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-sm shadow-md shadow-blue-100 transition cursor-pointer">
                        Simpan Penerima
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
