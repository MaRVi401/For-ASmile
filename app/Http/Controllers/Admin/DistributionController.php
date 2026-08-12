<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Beneficiary;
use App\Models\Distribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DistributionController extends Controller
{
    /**
     * Menampilkan daftar riwayat semua penyaluran donasi
     */
    public function index()
    {
        $distributions = Distribution::with(['campaign', 'beneficiary'])
            ->orderBy('distributed_at', 'desc')
            ->paginate(10);

        return view('admin.distributions.index', compact('distributions'));
    }

    /**
     * Menampilkan Form untuk mencatat Penyaluran Donasi baru
     */
    public function create()
    {
        $campaigns = Campaign::where('status', 'active')->get();
        $beneficiaries = Beneficiary::withCount('distributions')->orderBy('name', 'asc')->get();

        return view('admin.distributions.create', compact('campaigns', 'beneficiaries'));
    }

    /**
     * Menyimpan catatan penyaluran donasi beserta gambar dokumentasi
     */
    public function store(Request $request)
    {
        $request->validate([
            'campaign_id'         => 'required|exists:campaigns,id',
            'amount_distributed'  => 'required|numeric|min:1',
            'notes'               => 'nullable|string',
            'documentation_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',

            'beneficiary_id'      => 'nullable|exists:beneficiaries,id',
            'name'                => 'required_without:beneficiary_id|nullable|string|max:255',
            'address'             => 'nullable|string',
            'phone'               => 'nullable|string',
        ], [
            // Custom error messages
            'documentation_image.max'   => 'Ukuran foto tidak boleh lebih dari 2 MB.',
            'documentation_image.image' => 'File yang diunggah harus berupa gambar.',
            'documentation_image.mimes' => 'Format foto harus berupa JPEG, PNG, JPG, atau WEBP.',
        ]);

        try {
            // 1. Tentukan atau buat baru entitas penerima manfaat
            if ($request->filled('beneficiary_id')) {
                $beneficiaryId = $request->beneficiary_id;
            } else {
                $beneficiary = Beneficiary::firstOrCreate(
                    [
                        'name'  => $request->name,
                        'phone' => $request->phone,
                    ],
                    [
                        'address' => $request->address,
                    ]
                );
                $beneficiaryId = $beneficiary->id;
            }

            // 2. Upload gambar dokumentasi jika ada
            $imagePath = null;
            if ($request->hasFile('documentation_image')) {
                $imagePath = $request->file('documentation_image')->store('distributions', 'public');
            }

            // 3. Simpan log catatan penyaluran
            Distribution::create([
                'campaign_id'         => $request->campaign_id,
                'beneficiary_id'      => $beneficiaryId,
                'amount_distributed'  => $request->amount_distributed,
                'notes'               => $request->notes,
                'documentation_image' => $imagePath,
                'distributed_at'      => now(),
            ]);

            return redirect()->route('admin.distributions.index')
                ->with('success', 'Catatan penyaluran donasi berhasil disimpan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan catatan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan Form Edit Catatan
     */
    public function edit(Distribution $distribution)
    {
        $distribution->load(['campaign', 'beneficiary']);
        return view('admin.distributions.edit', compact('distribution'));
    }

    /**
     * Update Catatan Penyaluran, Gambar Dokumentasi, & Profil Penerima
     */
    public function update(Request $request, Distribution $distribution)
    {
        $request->validate([
            'notes'               => 'nullable|string',
            'address'             => 'nullable|string',
            'phone'               => 'nullable|string',
            'documentation_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            // Custom error messages
            'documentation_image.max'   => 'Ukuran foto tidak boleh lebih dari 2 MB.',
            'documentation_image.image' => 'File yang diunggah harus berupa gambar.',
            'documentation_image.mimes' => 'Format foto harus berupa JPEG, PNG, JPG, atau WEBP.',
        ]);

        try {
            $dataToUpdate = ['notes' => $request->notes];

            // Cek jika user mengunggah gambar baru
            if ($request->hasFile('documentation_image')) {
                // Hapus gambar lama dari storage jika ada
                if ($distribution->documentation_image && Storage::disk('public')->exists($distribution->documentation_image)) {
                    Storage::disk('public')->delete($distribution->documentation_image);
                }

                $dataToUpdate['documentation_image'] = $request->file('documentation_image')->store('distributions', 'public');
            }

            $distribution->update($dataToUpdate);

            $distribution->beneficiary->update([
                'address' => $request->address,
                'phone'   => $request->phone,
            ]);

            return redirect()->route('admin.distributions.index')
                ->with('success', 'Catatan berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Hapus Catatan Penyaluran beserta berkas gambar di Storage
     */
    public function destroy(Distribution $distribution)
    {
        try {
            // Hapus berkas gambar dari storage sebelum record di database dihapus
            if ($distribution->documentation_image && Storage::disk('public')->exists($distribution->documentation_image)) {
                Storage::disk('public')->delete($distribution->documentation_image);
            }

            $distribution->delete();

            return redirect()->route('admin.distributions.index')
                ->with('success', 'Catatan penyaluran donasi berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus catatan: ' . $e->getMessage());
        }
    }
}
