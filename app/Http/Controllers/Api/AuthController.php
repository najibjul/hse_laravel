<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

            return response()->json([
                'token' => $token,
                'user' => $user
            ]);
        }

        return response()->json(['message' => 'NIP & Password salah'], 401);
    }
}
