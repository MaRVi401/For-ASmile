@extends('admin.layouts.app')

@section('title', 'Master Data Penerima Santunan')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Master Data Penerima Santunan</h2>
                <p class="text-slate-500 text-sm mt-1">Daftar menyeluruh profil warga yang telah terdata menerima manfaat
                    program sosial.</p>
            </div>
            <a href="{{ route('admin.beneficiaries.create') }}"
                class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium shadow-md shadow-blue-200 transition duration-200 cursor-pointer">
                <i class="ti ti-plus text-lg"></i> Tambah Penerima
            </a>
        </div>

        {{-- Toast / Alert Notifikasi --}}
        @if (session('success'))
            <div
                class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-3 text-sm font-semibold">
                <i class="ti ti-circle-check text-xl text-emerald-500"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-200 text-slate-400 text-xs font-bold uppercase tracking-wider">
                            <th class="px-6 py-4" style="width: 8%">No</th>
                            <th class="px-6 py-4" style="width: 30%">Nama Lengkap</th>
                            <th class="px-6 py-4" style="width: 17%">Nomor HP / WA</th>
                            <th class="px-6 py-4" style="width: 25%">Alamat Tinggal</th>
                            <th class="px-6 py-4" style="width: 10%">Total Menerima</th>
                            <th class="px-6 py-4 text-right" style="width: 10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y border-slate-100 text-sm font-medium text-slate-700">
                        @forelse($beneficiaries as $index => $b)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4 text-slate-400 font-bold">
                                    {{ $beneficiaries->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs capitalize shrink-0">
                                            {{ substr($b->name, 0, 2) }}
                                        </div>
                                        <p class="font-semibold text-slate-800">{{ $b->name }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-600 font-normal">
                                    {{ $b->phone ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-slate-500 font-normal">
                                    <p class="max-w-xs truncate" title="{{ $b->address }}">{{ $b->address ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 px-2.5 py-1 rounded-full text-xs font-bold">
                                        {{ $b->distributions_count }} Kali
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.beneficiaries.edit', $b->id) }}"
                                            class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition cursor-pointer"
                                            title="Edit Profil">
                                            <i class="ti ti-edit text-lg"></i>
                                        </a>
                                        <form action="{{ route('admin.beneficiaries.destroy', $b->id) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDeleteBeneficiary(this)"
                                                class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition cursor-pointer"
                                                title="Hapus Penerima">
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
                                        <i class="ti ti-users-toggle text-4xl text-slate-300"></i>
                                        <p class="text-base font-semibold text-slate-500">Belum Ada Data Penerima</p>
                                        <p class="text-sm font-normal text-slate-400">Data penerima santunan akan otomatis
                                            tercatat ketika Anda mengisi Log Penyaluran Donasi.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($beneficiaries->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $beneficiaries->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function confirmDeleteBeneficiary(button) {
            Swal.fire({
                title: 'Hapus data penerima?',
                text: "PERINGATAN: Menghapus data orang ini juga akan menghapus seluruh catatan riwayat distribusi santunan yang terikat dengannya!",
                icon: 'danger',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus Semua!',
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
