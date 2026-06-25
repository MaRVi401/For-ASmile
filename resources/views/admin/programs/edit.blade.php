@extends('admin.layouts.app')

@section('title', 'Edit Program')

@section('content')
<div class="space-y-6 max-w-3xl mx-auto">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.programs.index') }}" class="p-2.5 bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 rounded-xl transition shadow-xs cursor-pointer" title="Kembali">
            <i class="ti ti-arrow-left text-lg"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Edit Program Kerja</h2>
            <p class="text-slate-500 text-sm mt-1">Perbarui detail, gambar, atau ubah relasi kelompok kampanye bulanan program ini.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <form action="{{ route('admin.programs.update', $program->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="campaign_id" class="block text-slate-700 text-sm font-semibold mb-2">Hubungkan ke Kampanye Bulanan</label>
                <select name="campaign_id" id="campaign_id" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition @error('campaign_id') border-red-500 @enderror" required>
                    @foreach($campaigns as $campaign)
                        <option value="{{ $campaign->id }}" {{ old('campaign_id', $program->campaign_id) == $campaign->id ? 'selected' : '' }}>
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
                <input type="text" name="program_name" id="program_name" value="{{ old('program_name', $program->program_name) }}" 
                    class="w-full px-4 py-2.5 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition @error('program_name') border-red-500 @else border-slate-300 @enderror" 
                    required placeholder="Contoh: Program Makan Siang Sehat & Bergizi">
                @error('program_name')
                    <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-slate-700 text-sm font-semibold mb-2">Gambar / Poster Program Kerja</label>
                
                <div class="mb-3 flex items-center gap-4">
                    @if($program->image_url)
                        <div>
                            <p class="text-xs text-slate-400 mb-1">Poster Saat Ini:</p>
                            <img src="{{ asset('storage/' . $program->image_url) }}" alt="Poster Saat Ini" class="h-28 w-auto object-cover rounded-xl border border-slate-200 shadow-xs">
                        </div>
                    @endif
                    <div id="preview-box" class="hidden">
                        <p class="text-xs text-blue-500 mb-1">Poster Baru (Pratinjau):</p>
                        <img id="output-image" class="h-28 w-auto object-cover rounded-xl border border-blue-200 shadow-xs">
                    </div>
                </div>

                <input type="file" name="image" id="image" accept="image/*"
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                    onchange="previewImage(event)">
                <p class="text-xs text-slate-400 mt-1">Biarkan kosong jika Anda tidak ingin mengganti gambar poster.</p>
                @error('image')
                    <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-slate-700 text-sm font-semibold mb-2">Deskripsi / Detail Kebutuhan Program (Opsional)</label>
                <textarea name="description" id="description" rows="4" 
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition" 
                    placeholder="Tuliskan detail mengenai program kerja ini...">{{ old('description', $program->description) }}</textarea>
                @error('description')
                    <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.programs.index') }}" class="px-5 py-2.5 border border-slate-200 hover:bg-slate-50 text-slate-600 rounded-xl font-medium transition cursor-pointer">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium shadow-md shadow-blue-200 transition duration-200 cursor-pointer">
                    Perbarui Program
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