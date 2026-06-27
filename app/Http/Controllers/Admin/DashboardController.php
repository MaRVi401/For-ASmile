<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Program;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Menghitung total data metrik ringkasan
        $totalCampaigns = Campaign::count();
        $totalPrograms = Program::count();
        
        // Total dana terkumpul murni dari transaksi yang sukses (settlement)
        $totalFunds = Transaction::where('status', 'settlement')->sum('amount');

        // 2. Mengambil 5 riwayat donasi terbaru yang sukses masuk
        $recentDonations = Transaction::with(['user', 'campaign'])
            ->where('status', 'settlement')
            ->latest()
            ->take(5)
            ->get();

        // 3. Mengambil tren data grafik donasi bulanan (6 bulan terakhir)
        $monthlyData = Transaction::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw("SUM(amount) as total")
            )
            ->where('status', 'settlement')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->take(6)
            ->get();

        // Mempersiapkan label bulan dan nilai untuk Chart.js
        $chartLabels = [];
        $chartValues = [];

        foreach ($monthlyData as $data) {
            $chartLabels[] = \Carbon\Carbon::parse($data->month . '-01')->translatedFormat('F Y');
            $chartValues[] = (int) $data->total;
        }

        return view('admin.dashboard', compact(
            'totalCampaigns', 
            'totalPrograms', 
            'totalFunds', 
            'recentDonations',
            'chartLabels',
            'chartValues'
        ));
    }
}