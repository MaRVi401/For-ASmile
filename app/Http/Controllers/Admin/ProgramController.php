<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProgramController extends Controller
{
    // Menampilkan semua program kerja beserta infomasi kampanye induknya
    public function index()
    {
        $programs = Program::with('campaign')->orderBy('created_at', 'desc')->get();
        return view('admin.programs.index', compact('programs'));
    }

    // Menampilkan form tambah program & mengambil daftar kampanye untuk dropdown select
    public function create()
    {
        $campaigns = Campaign::orderBy('month', 'desc')->get();
        return view('admin.programs.create', compact('campaigns'));
    }

    // Menyimpan data program baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'campaign_id' => 'required|exists:campaigns,id',
            'program_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048', // Poster wajib diisi untuk program
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('programs', 'public');
            $validated['image_url'] = $path;
        }

        Program::create($validated);

        return redirect()->route('admin.programs.index')->with('success', 'Program kerja berhasil ditambahkan!');
    }

    // Menampilkan form edit program
    public function edit(Program $program)
    {
        $campaigns = Campaign::orderBy('month', 'desc')->get();
        return view('admin.programs.edit', compact('program', 'campaigns'));
    }

    // Memperbarui data program kerja
    public function update(Request $request, Program $program)
    {
        $validated = $request->validate([
            'campaign_id' => 'required|exists:campaigns,id',
            'program_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($program->image_url) {
                Storage::disk('public')->delete($program->image_url);
            }
            $path = $request->file('image')->store('programs', 'public');
            $validated['image_url'] = $path;
        }

        $program->update($validated);

        return redirect()->route('admin.programs.index')->with('success', 'Program kerja berhasil diperbarui!');
    }

    // Menghapus data program kerja
    public function destroy(Program $program)
    {
        if ($program->image_url) {
            Storage::disk('public')->delete($program->image_url);
        }
        $program->delete();

        return redirect()->route('admin.programs.index')->with('success', 'Program kerja berhasil dihapus!');
    }
}