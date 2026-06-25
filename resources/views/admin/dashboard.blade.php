@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Dashboard Utama</h2>
        <p class="text-slate-500 text-sm mt-1">Selamat datang kembali! Berikut adalah ringkasan sistem donasi saat ini.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Total Kampanye</p>
                <h3 class="text-3xl font-bold text-slate-800">0</h3>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center shadow-sm shadow-blue-100">
                <i class="ti ti-calendar-event text-2xl"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Total Program</p>
                <h3 class="text-3xl font-bold text-slate-800">0</h3>
            </div>
            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center shadow-sm shadow-indigo-100">
                <i class="ti ti-rocket text-2xl"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Dana Terkumpul</p>
                <h3 class="text-3xl font-bold text-slate-800">Rp 0</h3>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center shadow-sm shadow-emerald-100">
                <i class="ti ti-wallet text-2xl"></i>
            </div>
        </div>

    </div>
</div>
@endsection