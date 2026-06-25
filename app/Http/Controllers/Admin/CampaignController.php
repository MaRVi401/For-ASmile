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
        $campaigns = Campaign::orderBy('month', 'desc')->get();
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
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'month' => 'required|string|size:7|unique:campaigns,month', // Format YYYY-MM (e.g., 2026-05) dan unik per bulan
            'target_amount' => 'required|numeric|min:0',
            'status' => 'required|in:draft,upcoming,active,completed',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'month.unique' => 'Kampanye untuk bulan tersebut sudah pernah dibuat!',
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
            'status' => 'required|in:draft,upcoming,active,completed',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($campaign->image_url) {
                Storage::disk('public')->delete($campaign->image_url);
            }

            // Simpan gambar baru
            $path = $request->file('image')->store('campaigns', 'public');
            $validated['image_url'] = $path;
        }

        $campaign->update($validated);

        return redirect()->route('admin.campaigns.index')->with('success', 'Kampanye berhasil diperbarui!');
    }

    // 6. Menghapus Kampanye
    public function destroy(Campaign $campaign)
    {
        // Hapus gambar terkait jika ada
        if ($campaign->image_url) {
            Storage::disk('public')->delete($campaign->image_url);
        }
        
        $campaign->delete();
        return redirect()->route('admin.campaigns.index')->with('success', 'Kampanye berhasil dihapus!');
    }
}
