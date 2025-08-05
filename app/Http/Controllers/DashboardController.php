<?php

namespace App\Http\Controllers;

use App\Models\DailyCheck;
use App\Models\QrpDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $qrpDetails = QrpDetail::whereHas('dailyCheck', function($q){
            $q->where('user_id', Auth::user()->id);
        })
        ->whereDate('created_at', '>=', Carbon::now()->subMonth()->firstOfMonth())
        ->get();

        $waiting = $qrpDetails->filter(function($q){
            return $q->qrp_status_id == 1 || $q->qrp_status_id == 4;
        })->count();

        $inProgress = $qrpDetails->filter(function($q){
            return $q->qrp_status_id == 2;
        })->count();

        $close = $qrpDetails->filter(function($q){
            return $q->qrp_status_id == 5;
        })->count();

        $reject = $qrpDetails->filter(function($q){
            return $q->qrp_status_id == 6 || $q->qrp_status_id == 6;
        })->count();

        $todayChecked = DailyCheck::where('user_id', Auth::user()->id)->whereDate('created_at', now())->count();

        return view('dashboard', compact('waiting', 'inProgress', 'close', 'reject', 'todayChecked'));
    }
}
