<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Exports\TransactionsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barrier\DomPDF\Facade\Pdf; // Pastikan alias PDF sudah terdeteksi, atau gunakan Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['user', 'campaign'])->latest()->paginate(10);
        return view('admin.transactions.index', compact('transactions'));
    }

    // Fungsi Ekspor Excel
    public function exportExcel()
    {
        return Excel::download(new TransactionsExport, 'Laporan_Transaksi_Donasi_' . date('Y-m-d') . '.xlsx');
    }

    // Fungsi Ekspor PDF
    public function exportPdf()
    {
        // Ambil data tanpa pagination untuk cetak laporan utuh
        $transactions = Transaction::with(['user', 'campaign'])->latest()->get();
        
        // Load view khusus untuk format kertas cetak PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.transactions.pdf', compact('transactions'));
        
        // Mengatur ukuran kertas menjadi A4 Landscape agar tabel muat dengan lega
        return $pdf->setPaper('a4', 'landscape')->download('Laporan_Transaksi_Donasi_' . date('Y-m-d') . '.pdf');
    }
}