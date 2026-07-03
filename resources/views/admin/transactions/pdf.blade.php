<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Donasi - For A Smile</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; font-size: 18px; color: #111; font-weight: bold; }
        .header p { margin: 5px 0 0; color: #666; font-size: 12px; }
        table { border-collapse: collapse; margin-top: 10px; width: 100%; }
        th, td { border: 1px solid #e2e8f0; padding: 10px 8px; text-align: left; }
        th { background-color: #f8fafc; color: #64748b; font-weight: bold; font-size: 11px; text-transform: uppercase; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 10px; font-weight: bold; inline-block; }
        .badge-success { background: #dcfce7; color: #15803d; }
        .badge-pending { background: #fef3c7; color: #b45309; }
        .badge-danger { background: #fee2e2; color: #b91c1c; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
    </style>
</head>
<body>

    <div class="header">
        <h2>LAPORAN TRANSAKSI DONASI - FOR A SMILE</h2>
        <p>Dicetak pada: {{ date('d F Y, H:i') }} WIB</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID Order / Waktu</th>
                <th>Donatur</th>
                <th>Alokasi Kampanye</th>
                <th>Metode</th>
                <th>Status</th>
                <th>Nominal</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($transactions as $transaction)
                @php 
                    if($transaction->status === 'settlement' || $transaction->status === 'success') {
                        $total += $transaction->amount; 
                    }
                @endphp
                <tr>
                    <td>
                        <span class="font-bold">#{{ $transaction->order_id }}</span><br>
                        <small style="color: #94a3b8;">{{ $transaction->created_at->format('d M Y, H:i') }} WIB</small>
                    </td>
                    <td>
                        {{ $transaction->user->name ?? 'Anonim' }}<br>
                        <small style="color: #94a3b8;">{{ $transaction->user->email ?? '-' }}</small>
                    </td>
                    <td>{{ $transaction->campaign->title ?? 'Umum / Global' }}</td>
                    <td>{{ strtoupper(str_replace('_', ' ', $transaction->payment_type ?? 'TRANSFER')) }}</td>
                    <td>
                        @if ($transaction->status === 'settlement' || $transaction->status === 'success')
                            <span class="badge badge-success">BERHASIL</span>
                        @elseif($transaction->status === 'pending')
                            <span class="badge badge-pending">PENDING</span>
                        @else
                            <span class="badge badge-danger">GAGAL</span>
                        @endif
                    </td>
                    <td class="text-right font-bold">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr style="background: #f8fafc;">
                <td colspan="5" class="text-right font-bold" style="padding: 12px; font-size: 12px; color: #475569;">Total Donasi Berhasil Cair:</td>
                <td class="text-right font-bold" style="color: #2563eb; font-size: 13px; padding: 12px;">
                    Rp {{ number_format($total, 0, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>

</body>
</html>