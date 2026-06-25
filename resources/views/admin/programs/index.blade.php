@extends('admin.layouts.app')

@section('title', 'Kelola Program')

@section('content')
    <div class="space-y-6">
        <!-- Header & Tombol Tambah -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Kelola Program Kerja</h2>
                <p class="text-slate-500 text-sm mt-1">Daftar aksi nyata spesifik donasi yang dikelompokkan ke dalam kampanye
                    bulanan.</p>
            </div>
            <a href="{{ route('admin.programs.create') }}"
                class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium shadow-md shadow-blue-200 transition duration-200 cursor-pointer">
                <i class="ti ti-plus text-lg"></i> Tambah Program
            </a>
        </div>

        <!-- Tabel Data -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-200 text-slate-400 text-xs font-bold uppercase tracking-wider">
                            <th class="px-6 py-4">Program Kerja</th>
                            <th class="px-6 py-4">Bagian Dari Kampanye</th>
                            <th class="px-6 py-4">Deskripsi</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y border-slate-100 text-sm font-medium text-slate-700">
                        @forelse($programs as $program)
                            <tr class="hover:bg-slate-50/50 transition">
                                <!-- Thumbnail & Nama Program -->
                                <td class="px-6 py-4 flex items-center gap-3">
                                    <div
                                        class="w-12 h-12 rounded-lg overflow-hidden bg-slate-100 border border-slate-200 shrink-0">
                                        @if ($program->image_url)
                                            <img src="{{ asset('storage/' . $program->image_url) }}" alt="Poster Program"
                                                class="w-full h-full object-cover">
                                        @else
                                            <div
                                                class="w-full h-full flex items-center justify-center text-slate-400 bg-slate-100">
                                                <i class="ti ti-photo text-xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800">{{ $program->program_name }}</p>
                                        <p class="text-xs text-slate-400 font-normal mt-0.5">Dibuat pada:
                                            {{ $program->created_at->translatedFormat('d M Y') }}</p>
                                    </div>
                                </td>
                                <!-- Kampanye Bulanan Induk -->
                                <td class="px-6 py-4">
                                    @if ($program->campaign)
                                        <div class="space-y-0.5">
                                            <p class="text-slate-800 font-semibold">{{ $program->campaign->title }}</p>
                                            <span
                                                class="inline-block bg-blue-50 text-blue-700 px-2 py-0.5 rounded text-xs font-bold">
                                                {{ \Carbon\Carbon::parse($program->campaign->month . '-01')->translatedFormat('F Y') }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-red-500 text-xs bg-red-50 px-2 py-0.5 rounded font-semibold">Tanpa
                                            Kampanye</span>
                                    @endif
                                </td>
                                <!-- Deskripsi Singkat -->
                                <td class="px-6 py-4 text-slate-500 font-normal max-w-xs truncate">
                                    {{ $program->description ?? 'Tidak ada deskripsi program.' }}
                                </td>
                                <!-- Tombol Aksi -->
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Tombol Edit -->
                                        <a href="{{ route('admin.programs.edit', $program->id) }}"
                                            class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition cursor-pointer"
                                            title="Edit">
                                            <i class="ti ti-edit text-lg"></i>
                                        </a>
                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('admin.programs.destroy', $program->id) }}" method="POST"
                                            class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete(this)"
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
                                <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <i class="ti ti-rocket-off text-4xl text-slate-300"></i>
                                        <p class="text-base font-semibold text-slate-500">Belum Ada Data Program</p>
                                        <p class="text-sm font-normal text-slate-400">Silakan klik tombol "Tambah Program"
                                            di atas untuk menghubungkan aksi nyata ke kampanye bulanan.</p>
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

@push('scripts')
    <script>
        function confirmDelete(button) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus beserta file gambarnya tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626', // Merah-600
                cancelButtonColor: '#64748b', // Slate-500
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-4 py-2 font-medium',
                    cancelButton: 'rounded-xl px-4 py-2 font-medium'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Cari form pembungkus tombol ini dan lakukan submit
                    button.closest('form').submit();
                }
            });
        }
    </script>
@endpush
