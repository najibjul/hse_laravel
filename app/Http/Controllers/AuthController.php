<?php

namespace App\Http\Controllers;

use App\Models\PasswordHistory;
use App\Rules\MatchOldPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function loginPost(Request $request)
    {
        $request->validate(
            [
                'nip' => 'required',
                'password' => [
                    'required',
                    'min:8',
                    'regex:/[A-Z]/',
                    'regex:/[a-z]/',
                    'regex:/[0-9]/',
                    'regex:/[@$!%*#?&.,]/',
                ],
            ],
            [
                'nip.required' => 'NIP wajib diisi',
                'password.required' => 'Password wajib diisi',
                'password.min' => 'Password minimal 8 karakter',
                'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan simbol',
            ]
        );

        $credentials = $request->only('nip', 'password');
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {

            $user = Auth::user();

            if ($request->is('api/*')) {
                $token = $user->createToken('halo-hse')->plainTextToken;

                return response()->json([
                    'token' => $token,
                    'user' => $user
                ]);
            }

            if (!$user->google2fa_secret) {
                return redirect()->route('mfa.setup');
            }

            return redirect()->route('mfa.challenge');
        }

        if ($request->is('api/*')) {
            return response()->json(['message' => 'NIP & Password salah'], 401);
        }

        session()->flash('unauthenticated', 'NIP & Password salah');
        return back();
    }

    public function logout()
    {
        /** @var \App\Models\User|null $user */

        $user = Auth::user();
        $user->setRememberToken(null);
        $user->save();
        Auth::logout();
        session()->forget('mfa_passed');
        return redirect()->route('login');
    }

    public function changePassword()
    {
        return view('change-password');
    }

    public function changePasswordPost(Request $request)
    {
        $request->validate(
            [
                'old_password' => ['required' , new MatchOldPassword],
                'password' => [
                    'required',
                    'confirmed',
                    'min:8',
                    'regex:/[A-Z]/',
                    'regex:/[a-z]/',
                    'regex:/[0-9]/',
                    'regex:/[@$!%*#?&.,]/',
                ],
            ],
            [
                'old_password.required' => 'Password lama wajib diisi',
                'password.required' => 'Password wajib diisi',
                'password.confirmed' => 'Konfirmasi password tidak sama',
                'password.min' => 'Password minimal 8 karakter',
                'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan simbol',
            ]
        );

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $passwordHistories = PasswordHistory::where('user_id', $user->id)->get();
        $countPassword = count($passwordHistories);

        $hashedPassword = Hash::make($request->password);

        foreach ($passwordHistories as $passwordHistory) {
            if (Hash::check($request->password, $passwordHistory->password)) {
                return back()->withErrors(['password' => 'Password sudah pernah digunakan.']);
            }
        }

        if ($countPassword >= 10) {
            PasswordHistory::orderBy('id')->first()->delete();
        }

        PasswordHistory::create([
            'user_id' => $user->id,
            'password' => $hashedPassword
        ]);

        $user->password = $hashedPassword;
        $user->must_change_password = true;
        $user->password_expire_at = Carbon::now()->addDays(90);
        $user->save();
        return redirect()->route('dashboard')->with('success', 'Password berhasil diubah.');
    }
}
