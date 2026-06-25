@extends('admin.layouts.app')

@section('title', 'Tambah Program Baru')

@section('content')
<div class="space-y-6 max-w-3xl mx-auto">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.programs.index') }}" class="p-2.5 bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 rounded-xl transition shadow-xs cursor-pointer" title="Kembali">
            <i class="ti ti-arrow-left text-lg"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Tambah Program Kerja Baru</h2>
            <p class="text-slate-500 text-sm mt-1">Buat aksi nyata baru dan hubungkan ke dalam wadah kampanye bulanan.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <form action="{{ route('admin.programs.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label for="campaign_id" class="block text-slate-700 text-sm font-semibold mb-2">Hubungkan ke Kampanye Bulanan</label>
                <select name="campaign_id" id="campaign_id" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition @error('campaign_id') border-red-500 @enderror" required>
                    <option value="" disabled selected>-- Pilih Periode Kampanye --</option>
                    @foreach($campaigns as $campaign)
                        <option value="{{ $campaign->id }}" {{ old('campaign_id') == $campaign->id ? 'selected' : '' }}>
                            {{ $campaign->title }} ({{ \Carbon\Carbon::parse($campaign->month . '-01')->translatedFormat('F Y') }})
                        </option>
                    @endforeach
                </select>
                @error('campaign_id')
                    <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="program_name" class="block text-slate-700 text-sm font-semibold mb-2">Nama Program Kerja</label>
                <input type="text" name="program_name" id="program_name" value="{{ old('program_name') }}" 
                    class="w-full px-4 py-2.5 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition @error('program_name') border-red-500 @else border-slate-300 @enderror" 
                    required placeholder="Contoh: Program Makan Siang Sehat & Bergizi">
                @error('program_name')
                    <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="image" class="block text-slate-700 text-sm font-semibold mb-2">Gambar / Poster Program Kerja</label>
                <div class="flex items-center justify-center w-full">
                    <label for="image" class="flex flex-col items-center justify-center w-full h-40 border-2 border-slate-300 border-dashed rounded-xl cursor-pointer bg-slate-50/50 hover:bg-slate-50 transition">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6 text-slate-400">
                            <i class="ti ti-cloud-upload text-3xl mb-2"></i>
                            <p class="mb-1 text-sm font-semibold">Klik untuk unggah poster gambar</p>
                            <p class="text-xs">PNG, JPG, JPEG, atau WEBP (Maks. 2MB)</p>
                        </div>
                        <input type="file" name="image" id="image" accept="image/*" class="hidden" required onchange="previewImage(event)" />
                    </label>
                </div>
                <div id="preview-box" class="mt-3 hidden">
                    <img id="output-image" class="h-28 w-auto object-cover rounded-xl border border-slate-200 shadow-xs">
                </div>
                @error('image')
                    <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-slate-700 text-sm font-semibold mb-2">Deskripsi / Detail Kebutuhan Program (Opsional)</label>
                <textarea name="description" id="description" rows="4" 
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition" 
                    placeholder="Tuliskan target sasaran penerima manfaat atau rincian kegiatan operasional dari program ini...">{{ old('description') }}</textarea>
                @error('description')
                    <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.programs.index') }}" class="px-5 py-2.5 border border-slate-200 hover:bg-slate-50 text-slate-600 rounded-xl font-medium transition cursor-pointer">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium shadow-md shadow-blue-200 transition duration-200 cursor-pointer">
                    Simpan Program
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('output-image');
            var box = document.getElementById('preview-box');
            output.src = reader.result;
            box.classList.remove('hidden');
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection