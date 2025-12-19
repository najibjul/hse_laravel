<?php

namespace App\Http\Controllers;

use App\Models\PasswordHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
                'min:8',
                'regex:/[A-Z]/',     
                'regex:/[a-z]/',     
                'regex:/[0-9]/',     
                'regex:/[@$!%*#?&.,]/',
                'confirmed'
            ],
        ]);
        
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password salah.']);
        }

        $passwordHistories = PasswordHistory::where('user_id', $user->id)->get();
        $countPassword = count($passwordHistories);

        $hashedPassword = Hash::make($request->password);

        
            foreach ($passwordHistories as $passwordHistory) {
                if (Hash::check($request->old_password, $passwordHistory->password)) {
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

        return back()->with('success', 'Password berhasil diubah.');
    }
}
