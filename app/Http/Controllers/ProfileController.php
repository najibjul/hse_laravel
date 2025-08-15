<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile');
    }

    public function update(Request $request) 
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',     
                'regex:/[a-z]/',     
                'regex:/[0-9]/',     
                'regex:/[@$!%*#?&]/',
                'confirmed'
            ],
        ]);
        
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password salah.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password berhasil diubah.');
    }
}
