@extends('admin.layouts.app')

@section('title', 'Kelola Kampanye')

@section('content')
    <div class="space-y-6">
        <!-- Header & Tombol Tambah -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Kelola Kampanye Bulanan</h2>
                <p class="text-slate-500 text-sm mt-1">Wadah utama donasi terikat waktu untuk mengelompokkan program kerja.
                </p>
            </div>
            <a href="{{ route('admin.campaigns.create') }}"
                class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium shadow-md shadow-blue-200 transition duration-200 cursor-pointer">
                <i class="ti ti-plus text-lg"></i> Tambah Kampanye
            </a>
        </div>

        <!-- Tabel Data -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-200 text-slate-400 text-xs font-bold uppercase tracking-wider">
                            <th class="px-6 py-4">Nama Kampanye / Judul</th>
                            <th class="px-6 py-4">Target Bulan</th>
                            <th class="px-6 py-4">Target Dana</th>
                            <th class="px-6 py-4">Dana Terkumpul</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y border-slate-100 text-sm font-medium text-slate-700">
                        @forelse($campaigns as $campaign)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4 flex items-center gap-3">
                                    <div
                                        class="w-12 h-12 rounded-lg overflow-hidden bg-slate-100 border border-slate-200 shrink-0">
                                        @if ($campaign->image_url)
                                            <img src="{{ asset('storage/' . $campaign->image_url) }}" alt="Poster"
                                                class="w-full h-full object-cover">
                                        @else
                                            <div
                                                class="w-full h-full flex items-center justify-center text-slate-400 bg-slate-100">
                                                <i class="ti ti-photo text-xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800">{{ $campaign->title }}</p>
                                        <p class="text-xs text-slate-400 font-normal mt-0.5 max-w-xs truncate">
                                            {{ $campaign->description ?? 'Tidak ada deskripsi.' }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-500">
                                    <span class="bg-slate-100 text-slate-700 px-2.5 py-1 rounded-md text-xs font-semibold">
                                        {{ \Carbon\Carbon::parse($campaign->month . '-01')->translatedFormat('F Y') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-600">
                                    Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-emerald-600">Rp
                                        {{ number_format($campaign->current_amount, 0, ',', '.') }}</p>
                                    <!-- Progress Bar Kecil -->
                                    <div class="w-full bg-slate-100 h-1.5 rounded-full mt-1.5 overflow-hidden">
                                        @php
                                            $percentage =
                                                $campaign->target_amount > 0
                                                    ? ($campaign->current_amount / $campaign->target_amount) * 180
                                                    : 0;
                                            $percentage = $percentage > 180 ? 180 : $percentage;
                                        @endphp
                                        <div class="bg-emerald-500 h-full rounded-full" style="width: {{ $percentage }}%">
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($campaign->status === 'active')
                                        <span
                                            class="inline-flex items-center gap-1 bg-green-50 text-green-700 px-2.5 py-1 rounded-full text-xs font-semibold">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span> Aktif
                                        </span>
                                    @elseif($campaign->status === 'upcoming')
                                        <span
                                            class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 px-2.5 py-1 rounded-full text-xs font-semibold">
                                            <span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span> Akan Datang
                                        </span>
                                    @elseif($campaign->status === 'completed')
                                        <span
                                            class="inline-flex items-center gap-1 bg-slate-100 text-slate-600 px-2.5 py-1 rounded-full text-xs font-semibold">
                                            Selesai
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 px-2.5 py-1 rounded-full text-xs font-semibold">
                                            Draft
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Tombol Edit -->
                                        <a href="{{ route('admin.campaigns.edit', $campaign->id) }}"
                                            class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition cursor-pointer"
                                            title="Edit">
                                            <i class="ti ti-edit text-lg"></i>
                                        </a>
                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('admin.campaigns.destroy', $campaign->id) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus kampanye bulanan ini? Semua program di dalamnya juga akan ikut terhapus!');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition cursor-pointer"
                                                title="Hapus">
                                                <i class="ti ti-trash text-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <i class="ti ti-calendar-cancel text-4xl text-slate-300"></i>
                                        <p class="text-base font-semibold text-slate-500">Belum Ada Data Kampanye</p>
                                        <p class="text-sm font-normal text-slate-400">Silakan klik tombol "Tambah Kampanye"
                                            di atas untuk membuat wadah bulanan baru.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
