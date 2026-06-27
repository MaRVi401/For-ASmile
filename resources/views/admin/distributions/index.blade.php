@extends('admin.layouts.app')

@section('title', 'Log Penyaluran Donasi')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Log Penyaluran Donasi</h2>
                <p class="text-slate-500 text-sm mt-1">Catatan pelaporan distribusi santunan program sosial eksternal (Murni Pencatatan).</p>
            </div>
            <a href="{{ route('admin.distributions.create') }}"
                class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium shadow-md shadow-blue-200 transition duration-200 cursor-pointer">
                <i class="ti ti-plus text-lg"></i> Catat Penyaluran
            </a>
        </div>

        {{-- Notifikasi Sukses / Gagal Berbasis Toast / Alert Ringan --}}
        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-3 text-sm font-semibold">
                <i class="ti ti-circle-check text-xl text-emerald-500"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 text-xs font-bold uppercase tracking-wider">
                            <th class="px-6 py-4" style="width: 5%">No</th>
                            <th class="px-6 py-4" style="width: 25%">Penerima Santunan</th>
                            <th class="px-6 py-4" style="width: 25%">Keterangan Kampanye</th>
                            <th class="px-6 py-4" style="width: 15%">Nominal Saluran</th>
                            <th class="px-6 py-4" style="width: 15%">Waktu Distribusi</th>
                            <th class="px-6 py-4 text-right" style="width: 15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y border-slate-100 text-sm font-medium text-slate-700">
                        @forelse($distributions as $index => $dist)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4 text-slate-400 font-bold">
                                    {{ $distributions->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-semibold text-slate-800">{{ $dist->beneficiary->name }}</p>
                                        <div class="flex items-center gap-3 text-xs text-slate-400 font-normal mt-0.5">
                                            <span class="flex items-center gap-1"><i class="ti ti-phone text-xs"></i> {{ $dist->beneficiary->phone ?? '-' }}</span>
                                            <span class="flex items-center gap-1 max-w-[180px] truncate"><i class="ti ti-map-pin text-xs"></i> {{ $dist->beneficiary->address ?? '-' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="bg-slate-100 text-slate-700 px-2.5 py-1 rounded-md text-xs font-semibold inline-block mb-1">
                                        {{ \Carbon\Carbon::parse($dist->campaign->month . '-01')->translatedFormat('F Y') }}
                                    </span>
                                    <p class="text-xs font-normal text-slate-500 max-w-[200px] truncate" title="{{ $dist->campaign->title }}">
                                        {{ $dist->campaign->title }}
                                    </p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-blue-600 font-bold">
                                        Rp {{ number_format($dist->amount_distributed, 0, ',', '.') }}
                                    </p>
                                </td>
                                <td class="px-6 py-4 text-slate-500 font-normal text-xs">
                                    <p class="font-medium text-slate-700">{{ $dist->distributed_at->translatedFormat('d M Y') }}</p>
                                    <p class="text-slate-400 mt-0.5">{{ $dist->distributed_at->format('H:i') }} WIB</p>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.distributions.edit', $dist->id) }}"
                                            class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-xl transition cursor-pointer"
                                            title="Edit Catatan">
                                            <i class="ti ti-edit text-lg"></i>
                                        </a>
                                        <form action="{{ route('admin.distributions.destroy', $dist->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDeleteLog(this)"
                                                class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition cursor-pointer"
                                                title="Hapus Log">
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
                                        <i class="ti ti-folder-off text-4xl text-slate-300"></i>
                                        <p class="text-base font-semibold text-slate-500">Belum Ada Catatan Penyaluran</p>
                                        <p class="text-sm font-normal text-slate-400">Silakan klik tombol "Catat Penyaluran" untuk menulis dokumentasi baru.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($distributions->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $distributions->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function confirmDeleteLog(button) {
            Swal.fire({
                title: 'Hapus catatan ini?',
                text: "Tindakan ini hanya membuang log dokumentasi penyaluran tanpa memengaruhi total donasi digital.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-4 py-2 font-medium',
                    cancelButton: 'rounded-xl px-4 py-2 font-medium'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    button.closest('form').submit();
                }
            });
        }
    </script>
@endpush