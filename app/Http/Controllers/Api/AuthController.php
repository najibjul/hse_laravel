<?php

namespace App\Http\Controllers\Api;

use App\Models\PasswordHistory;
use App\Models\User;
use App\Rules\ApiMatchOldPassword;
use App\Rules\MatchOldPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController
{
    public function post(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nip' => 'required',
            'password' => [
                'required',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&.,]/',
            ],
        ], [
            'nip.required' => 'NIP wajib diisi',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan simbol',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message'  => $validator->errors()->first()
            ], 422);
        }

        $credentials = $request->only('nip', 'password');
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {

            $user = Auth::user();

            $token = $user->createToken('halo-hse')->plainTextToken;

            $user = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'nip' => $user->nip,
                'department' => $user->department->department_name,
                'cost_center' => $user->costCenter ? substr($user->costCenter->cost_center_name, 0, 3) : '',
                'role' => $user->role?->role_name,
                'position' => $user->position?->position_name,
                'plant' => $user->plant?->plant_name,
                'leader_name' => $user->leader?->name,
                'leader_nip' => $user->leader?->nip,
            ];

            return response()->json([
                'token' => $token,
                'user' => $user
            ]);
        }

        return response()->json(['message' => 'NIP & Password salah'], 401);
    }

    public function changePasswordPost(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'old_password' => ['required' , new ApiMatchOldPassword($request->user())],
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
                'old_password.required' => 'Kata sandi lama wajib diisi',
                'password.required' => 'Kata sandi wajib diisi',
                'password.confirmed' => 'Konfirmasi Kata sandi tidak sama',
                'password.min' => 'Kata sandi minimal 8 karakter',
                'password.regex' => 'Kata sandi harus mengandung huruf besar, huruf kecil, angka, dan simbol',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'message'  => $validator->errors()->first()
            ], 422);
        }

        /** @var \App\Models\User $user */
        $user = $request->user();

        $passwordHistories = PasswordHistory::where('user_id', $user->id)->get();
        $countPassword = count($passwordHistories);

        $hashedPassword = Hash::make($request->password);

        foreach ($passwordHistories as $passwordHistory) {
            if (Hash::check($request->password, $passwordHistory->password)) {
                return response()->json([
                'message'  => "Kata sandi sudah pernah dipakai"
            ], 422);
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

        return response()->json(['message' => 'Ganti kata sandi sukses'], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->where('id', $request->user()->currentAccessToken()->id)->delete();
        return response()->json(['message' => 'Berhasil keluar']);
    }
}
