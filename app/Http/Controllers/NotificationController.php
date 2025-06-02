<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function update($id)
    {
        DB::beginTransaction();

        try {
            $notification = Notification::find($id);
            $target = $notification->target;
            $target_id = $notification->target_id;

            $notification->delete();

            DB::commit();

            if ($target == "qrp") {
                return redirect()->route('qrp.qrp-form-detail', encrypt($target_id));
            }

        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->withErrors('error', $th->getMessage());
        }
    }
}
