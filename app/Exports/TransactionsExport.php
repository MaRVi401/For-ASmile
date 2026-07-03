<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * Ambil seluruh data transaksi beserta relasinya
    */
    public function collection()
    {
        return Transaction::with(['user', 'campaign'])->latest()->get();
    }

    /**
    * Judul Kolom Excel
    */
    public function headings(): array
    {
        return [
            'ID Order',
            'Tanggal & Waktu',
            'Nama Donatur',
            'Email Donatur',
            'Alokasi Kampanye',
            'Nominal',
            'Metode Pembayaran',
            'Status',
        ];
    }

    /**
    * Mapping Data Per Baris
    */
    public function map($transaction): array
    {
        return [
            '#' . $transaction->order_id,
            $transaction->created_at->format('d M Y, H:i') . ' WIB',
            $transaction->user->name ?? 'Anonim / Hamba Allah',
            $transaction->user->email ?? '-',
            $transaction->campaign->title ?? 'Umum / Global',
            $transaction->amount,
            str_replace('_', ' ', strtoupper($transaction->payment_type ?? 'Gopay/Transfer')),
            strtoupper($transaction->status),
        ];
    }
}