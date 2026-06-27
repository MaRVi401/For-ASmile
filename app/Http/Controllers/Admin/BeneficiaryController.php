<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Beneficiary;
use Illuminate\Http\Request;

class BeneficiaryController extends Controller
{
    /**
     * Menampilkan daftar semua penerima santunan beserta total frekuensi menerima manfaat
     */
    public function index()
    {
        // Menggunakan withCount untuk menghitung otomatis berapa kali menerima santunan
        $beneficiaries = Beneficiary::withCount('distributions')
            ->orderBy('name', 'asc')
            ->paginate(10);

        return view('admin.beneficiaries.index', compact('beneficiaries'));
    }

    /**
     * Menampilkan form tambah penerima manfaat baru secara manual
     */
    public function create()
    {
        return view('admin.beneficiaries.create');
    }

    /**
     * Menyimpan data penerima manfaat baru secara manual ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        try {
            // Mencegah duplikasi data jika nama dan nomor hp yang diinput persis sama
            Beneficiary::firstOrCreate(
                [
                    'name'  => $request->name,
                    'phone' => $request->phone,
                ],
                [
                    'address' => $request->address,
                ]
            );

            return redirect()->route('admin.beneficiaries.index')
                ->with('success', 'Data penerima santunan berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan form edit profil penerima santunan
     */
    public function edit(Beneficiary $beneficiary)
    {
        return view('admin.beneficiaries.edit', compact('beneficiary'));
    }

    /**
     * Memperbarui data profil penerima santunan
     */
    public function update(Request $request, Beneficiary $beneficiary)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        try {
            $beneficiary->update([
                'name'    => $request->name,
                'phone'   => $request->phone,
                'address' => $request->address,
            ]);

            return redirect()->route('admin.beneficiaries.index')
                ->with('success', 'Data profil penerima santunan berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus total data penerima santunan dari sistem
     */
    public function destroy(Beneficiary $beneficiary)
    {
        try {
            // Hapus data orang tersebut (Otomatis menghapus riwayat distribusi jika cascade diset di migrasi)
            $beneficiary->delete();

            return redirect()->route('admin.beneficiaries.index')
                ->with('success', 'Data penerima santunan beserta seluruh riwayatnya berhasil dihapus dari sistem!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
