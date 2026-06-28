<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendResetPasswordCode;
use Carbon\Carbon;

class AuthController extends Controller
{
    // 1. REGISTER API
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Opsional: Langsung berikan token setelah register sukses
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil',
            'data' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 210);
    }

    // 2. LOGIN API
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Format email atau password salah',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah'
            ], 401);
        }

        // Hapus token lama jika ingin membatasi login hanya di satu device (opsional)
        $user->tokens()->delete();

        // Buat token baru
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 200);
    }

    // 3. LOGOUT API
    public function logout(Request $request)
    {
        // Menghapus token yang sedang digunakan saat ini
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ], 200);
    }

    // 4. ENDPOINT: MINTA KODE RESET (FORGOT PASSWORD)
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak terdaftar atau format salah',
                'errors' => $validator->errors()
            ], 422);
        }

        // Generate 6 digit angka random
        $code = rand(100000, 999999);

        // Simpan atau update kode di tabel password_reset_tokens
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($code), // Simpan versi hash demi keamanan
                'created_at' => Carbon::now()
            ]
        );

        // Kirim email ke user
        Mail::to($request->email)->send(new SendResetPasswordCode($code));

        return response()->json([
            'success' => true,
            'message' => 'Kode reset password telah dikirim ke email Anda.'
        ], 200);
    }

    // 5. ENDPOINT: SUBMIT PASSWORD BARU DENGAN KODE (RESET PASSWORD)
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|exists:users,email',
            'code' => 'required|numeric',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Ambil data token berdasarkan email
        $resetData = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$resetData) {
            return response()->json([
                'success' => false,
                'message' => 'Permintaan reset password tidak ditemukan.'
            ], 400);
        }

        // Cek apakah kode kedaluwarsa (misal > 60 menit)
        if (Carbon::parse($resetData->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return response()->json([
                'success' => false,
                'message' => 'Kode verifikasi telah kedaluwarsa, silakan minta kode baru.'
            ], 400);
        }

        // Validasi kecocokan kode input dengan token di database
        if (!Hash::check($request->code, $resetData->token)) {
            return response()->json([
                'success' => false,
                'message' => 'Kode verifikasi yang Anda masukkan salah.'
            ], 400);
        }

        // Update password user baru
        $user = User::where('email', $request->email)->first();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Hapus token yang sudah terpakai
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Opsional: Hapus semua token login aktif (logout dari semua device setelah ganti pass)
        $user->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password Anda berhasil diperbarui. Silakan login kembali.'
        ], 200);
    }
}
