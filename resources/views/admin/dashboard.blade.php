@extends('admin.layouts.app')

@section('title', 'Dashboard Utama')
@section('content')
    <div class="space-y-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Dashboard Utama</h2>
            <p class="text-slate-500 text-sm mt-1">Selamat datang kembali! Berikut adalah ringkasan performa sistem donasi
                saat ini.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl shadow-xs border border-slate-200 flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Total Kampanye</p>
                    <h3 class="text-3xl font-bold text-slate-800">{{ $totalCampaigns }}</h3>
                </div>
                <div
                    class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center shadow-xs shadow-blue-100">
                    <i class="ti ti-calendar-event text-2xl"></i>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-xs border border-slate-200 flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Total Program</p>
                    <h3 class="text-3xl font-bold text-slate-800">{{ $totalPrograms }}</h3>
                </div>
                <div
                    class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center shadow-xs shadow-indigo-100">
                    <i class="ti ti-rocket text-2xl"></i>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-xs border border-slate-200 flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Dana Terkumpul</p>
                    <h3 class="text-3xl font-bold text-emerald-600">Rp {{ number_format($totalFunds, 0, ',', '.') }}</h3>
                </div>
                <div
                    class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center shadow-xs shadow-emerald-100">
                    <i class="ti ti-wallet text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div
                class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl p-6 shadow-xs flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h4 class="text-base font-bold text-slate-800">Tren Donasi Masuk</h4>
                        <p class="text-xs text-slate-400">Statistik dana terkumpul sukses dalam periode bulan berjalan.</p>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 text-slate-600 rounded-md">6 Bulan
                        Terakhir</span>
                </div>
                <div class="relative w-full h-64">
                    <canvas id="donationTrendChart"></canvas>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs flex flex-col">
                <div class="mb-4">
                    <h4 class="text-base font-bold text-slate-800">Donasi Terbaru</h4>
                    <p class="text-xs text-slate-400">Daftar transaksi sukses terakhir dari para donatur.</p>
                </div>

                <div class="flow-root flex-1 overflow-y-auto max-h-[16.5rem] pr-1">
                    <ul role="list" class="-mb-8">
                        @forelse ($recentDonations as $index => $donation)
                            <li>
                                <div class="relative pb-5">
                                    @if ($index !== count($recentDonations) - 1)
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-slate-100"
                                            aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex space-x-3 items-start">
                                        <div>
                                            <span
                                                class="h-8 w-8 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center ring-8 ring-white">
                                                <i class="ti ti-circle-check text-base"></i>
                                            </span>
                                        </div>
                                        <div class="flex-1 min-w-0 pt-0.5">
                                            <p class="text-xs font-bold text-slate-800 truncate">
                                                {{ $donation->user->name ?? 'Donatur Anonim' }}
                                            </p>
                                            <p class="text-[11px] text-slate-400 truncate mt-0.5">
                                                Kampanye: <span
                                                    class="font-medium text-slate-600">{{ $donation->campaign->title ?? '-' }}</span>
                                            </p>
                                            <p class="text-[10px] text-slate-400 mt-0.5">
                                                {{ $donation->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                        <div class="text-right text-xs font-bold text-emerald-600 pt-0.5">
                                            +Rp {{ number_format($donation->amount, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <div class="flex flex-col items-center justify-center text-center py-12 text-slate-400">
                                <i class="ti ti-receipt-off text-3xl text-slate-300 mb-1"></i>
                                <p class="text-xs font-semibold">Belum Ada Transaksi</p>
                                <p class="text-[11px] font-normal">Laporan donasi sukses akan tampil otomatis di sini.</p>
                            </div>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('donationTrendChart').getContext('2d');

            // Menerima passing array data murni dari Controller PHP ke JavaScript
            const chartLabels = {!! json_encode($chartLabels) !!};
            const chartValues = {!! json_encode($chartValues) !!};

            // Jika data kosong, gunakan data dummy visual agar grafik tidak rusak
            const finalLabels = chartLabels.length ? chartLabels : ['Belum Ada Data'];
            const finalValues = chartValues.length ? chartValues : [0];

            // Buat efek gradien warna biru transparan di bawah garis grafik
            const gradient = ctx.createLinearGradient(0, 0, 0, 250);
            gradient.addColorStop(0, 'rgba(37, 99, 235, 0.2)'); // Blue-600 transparan
            gradient.addColorStop(1, 'rgba(37, 99, 235, 0.0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: finalLabels,
                    datasets: [{
                        label: ' Total Donasi (Rp)',
                        data: finalValues,
                        borderColor: '#2563eb', // Blue-600
                        borderWidth: 3,
                        backgroundColor: gradient,
                        fill: true,
                        tension: 0.35, // Membuat kelengkungan kurva garis menjadi smooth
                        pointBackgroundColor: '#2563eb',
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false // Menyembunyikan label kotak bawaan di atas chart
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            padding: 12,
                            backgroundColor: '#1e293b', // Slate-800
                            titleFont: {
                                size: 12,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 12
                            },
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        // Memformat angka tooltip ke format mata uang Rupiah
                                        label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false // Sembunyikan garis grid vertikal latar belakang
                            },
                            ticks: {
                                font: {
                                    size: 11,
                                    weight: '500'
                                },
                                color: '#64748b' // Slate-500
                            }
                        },
                        y: {
                            grid: {
                                color: '#f1f5f9' // Ganti warna garis grid horizontal menjadi abu-abu tipis
                            },
                            ticks: {
                                font: {
                                    size: 11
                                },
                                color: '#64748b',
                                callback: function(value) {
                                    // Sederhanakan angka sumbu Y agar rapi (misal: 1.000.000 menjadi 1M)
                                    if (value >= 1000000) {
                                        return 'Rp ' + (value / 1000000) + 'M';
                                    } else if (value >= 1000) {
                                        return 'Rp ' + (value / 1000) + 'K';
                                    }
                                    return 'Rp ' + value;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
