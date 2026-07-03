<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserProfileController extends Controller
{
    /**
     * Update data profil user yang sedang login.
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        // Validasi input data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            // Email harus unik, kecuali milik user itu sendiri yang sedang mengedit
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            // Password bersifat opsional, jika diisi wajib ada confirm password (confirmed)
            'password' => 'nullable|string|min:8|confirmed',
            // Validasi file avatar gambar (jpeg, png, jpg) max 2MB
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        // 1. Update nama dan email
        $user->name = $request->name;
        $user->email = $request->email;

        // 2. Update password jika user mengisi kolom password
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // 3. Proses upload avatar jika ada file baru yang diunggah
        if ($request->hasFile('avatar')) {
            // Hapus gambar profil lama jika ada di dalam storage public
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Simpan gambar baru ke folder 'avatars' di disk public
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        // Tambahkan properti dinamis berupa full URL gambar agar mempermudah frontend mobile/web
        $user->avatar_url = $user->avatar ? asset('storage/' . $user->avatar) : null;

        return response()->json([
            'success' => true,
            'message' => 'Profil Anda berhasil diperbarui.',
            'data' => $user
        ], 200);
    }
}