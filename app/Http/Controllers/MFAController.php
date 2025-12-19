<?php

namespace App\Http\Controllers;

use App\Models\PasswordHistory;
use App\Models\User;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MFAController extends Controller
{
    public function setup()
    {
        $google2fa = new Google2FA();
        $user = Auth::user();

        if (!$user->google2fa_secret) {
            /** @var \App\Models\User $user */
            $secret = $google2fa->generateSecretKey();
            $user->google2fa_secret = $secret;
            $user->save();
        }

        $google2fa_url = $google2fa->getQRCodeUrl(
            'safetyComitee',
            $user->nip,
            $user->google2fa_secret
        );

        $qrcode = QrCode::size(200)->generate($google2fa_url);

        $checkPassHist = PasswordHistory::where('user_id', $user->id)->count();

        if ($checkPassHist == 0) {
            PasswordHistory::create([
                'user_id' => $user->id,
                'password' => $user->password
            ]);
        }

        return view('mfa.setup', [
            'qrcode' => $qrcode,
            'secret' => $user->google2fa_secret,
        ]);
    }

    public function verify(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);

        $google2fa = new Google2FA();
        $user = Auth::user();

        $valid = $google2fa->verifyKey($user->google2fa_secret, $request->otp);

        if ($valid) {
            session(['mfa_passed' => true]);
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['otp' => 'Kode OTP salah']);
    }

    public function challenge()
    {
        return view('mfa.challenge');
    }

    public function reset($id) 
    {
        $user = User::find($id);
        $user->update([
            'remember_token' => null,
            'google2fa_secret' => null,
        ]);

        DB::table('sessions')->where('user_id', $id)->delete();

        session()->flash('success', 'MFA berhasil direset');
        return redirect()->route('admin.users.index');
    }
}
