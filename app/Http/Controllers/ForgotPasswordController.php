<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;
use App\Mail\ResetPasswordMail;

class ForgotPasswordController extends Controller
{
    /**
     * Show forgot password form
     */
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send reset link via email
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan di sistem kami.'])->withInput();
        }

        // Generate token
        $token = Str::random(64);

        // Delete old tokens for this email
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Insert new token
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        // Send email
        try {
            Mail::to($request->email)->send(new ResetPasswordMail($token, $request->email, $user->username));
        } catch (\Exception $e) {
            \Log::error('Failed to send reset password email: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Gagal mengirim email. Silakan coba lagi.'])->withInput();
        }

        return back()->with('status', 'Link reset password telah dikirim ke email Anda. Silakan cek inbox/spam.');
    }

    /**
     * Show reset password form
     */
    public function showResetForm(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Process password reset
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Find token record
        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record) {
            return back()->withErrors(['email' => 'Token reset tidak valid atau sudah kadaluarsa.']);
        }

        // Verify token
        if (!Hash::check($request->token, $record->token)) {
            return back()->withErrors(['email' => 'Token reset tidak valid.']);
        }

        // Check if token is expired (60 minutes)
        if (now()->diffInMinutes($record->created_at) > 60) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'Token sudah kadaluarsa. Silakan request ulang.']);
        }

        // Update password
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'User tidak ditemukan.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Delete token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('status', 'Password berhasil direset! Silakan login dengan password baru.');
    }
}
