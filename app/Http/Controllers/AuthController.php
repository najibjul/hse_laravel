<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function loginPost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nip' => 'required',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',     
                'regex:/[a-z]/',     
                'regex:/[0-9]/',     
                'regex:/[@$!%*#?&]/',
            ],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('nip', 'password');
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            return redirect()->route('dashboard');
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
        return redirect()->route('login');
    }
}
