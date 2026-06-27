<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CampaignController extends Controller
{
    // 1. Menampilkan Daftar Kampanye (Tabel)
    public function index()
    {
        // Mengambil data kampanye bulanan serta menghitung secara dinamis 
        // total nominal dari transaksi yang hanya berstatus 'settlement' (sukses)
        $campaigns = Campaign::withSum(['transactions' => function ($query) {
            $query->where('status', 'settlement');
        }], 'amount')
            ->withSum('distributions', 'amount_distributed')
            ->orderBy('month', 'desc')
            ->paginate(10);

        // Menyinkronkan nilai hitungan dinamis ke properti current_amount agar dibaca oleh Blade View
        foreach ($campaigns as $campaign) {
            $campaign->current_amount = $campaign->transactions_sum_amount ?? 0.00;
            $campaign->distributed_amount = $campaign->distributions_sum_amount_distributed ?? 0.00;
        }

        return view('admin.campaigns.index', compact('campaigns'));
    }

    // 2. Menampilkan Form Tambah Kampanye
    public function create()
    {
        return view('admin.campaigns.create');
    }

    // 3. Menyimpan Kampanye Baru ke Database
    public function store(Request $request)
    {
        $currentMonth = date('Y-m'); // Mengambil data bulan sekarang (e.g., '2026-06')

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            // Validasi agar tidak bisa memilih bulan yang sudah terlewat dari bulan sekarang
            'month' => 'required|string|size:7|unique:campaigns,month|after_or_equal:' . $currentMonth,
            'target_amount' => 'required|numeric|min:0',
            // Aturan diperketat hanya menerima status 'draft' dan 'active'
            'status' => 'required|in:draft,active',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'month.unique' => 'Kampanye untuk bulan tersebut sudah pernah dibuat!',
            'month.after_or_equal' => 'Target periode bulan tidak boleh memilih bulan yang sudah berlalu!',
            'status.in' => 'Status publikasi yang dipilih tidak valid!',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('campaigns', 'public');
            $validated['image_url'] = $path;
        }

        Campaign::create($validated);

        return redirect()->route('admin.campaigns.index')->with('success', 'Kampanye bulanan berhasil ditambahkan!');
    }

    // 4. Menampilkan Form Edit Kampanye
    public function edit(Campaign $campaign)
    {
        return view('admin.campaigns.edit', compact('campaign'));
    }

    // 5. Memperbarui Data Kampanye
    public function update(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'month' => 'required|string|size:7|unique:campaigns,month,' . $campaign->id,
            'target_amount' => 'required|numeric|min:0',
            // Aturan diperketat hanya menerima status 'draft' dan 'active'
            'status' => 'required|in:draft,active',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'status.in' => 'Status publikasi yang dipilih tidak valid!',
        ]);

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada di storage
            if ($campaign->image_url) {
                Storage::disk('public')->delete($campaign->image_url);
            }

            // Simpan gambar baru yang diunggah
            $path = $request->file('image')->store('campaigns', 'public');
            $validated['image_url'] = $path;
        }

        $campaign->update($validated);

        return redirect()->route('admin.campaigns.index')->with('success', 'Kampanye berhasil diperbarui!');
    }

    // 6. Menghapus Kampanye
    public function destroy(Campaign $campaign)
    {
        // Cek apakah kampanye memiliki program kerja terkait
        if ($campaign->programs()->exists()) {
            return redirect()->route('admin.campaigns.index')
                ->with('error', 'Kampanye tidak dapat dihapus karena masih memiliki program kerja aktif di dalamnya!');
        }

        // Hapus gambar terkait jika ada
        if ($campaign->image_url) {
            Storage::disk('public')->delete($campaign->image_url);
        }

        $campaign->delete();
        return redirect()->route('admin.campaigns.index')->with('success', 'Kampanye berhasil dihapus!');
    }
}
