<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DailyCheck;
use Illuminate\Http\Request;

class CheckController extends Controller
{
    public function reports(Request $request)
    {
        $limit = $request->limit;
        $offset = ($request->page - 1) * $limit;

        $reports = DailyCheck::select('id', 'activity', 'area', 'factor_id', 'check_status', 'created_at')
        ->offset($offset)
        ->limit($limit)
        ->where('user_id', $request->user()->id)
        ->with(['factor' => fn($q) => $q->select('id', 'factor_name') ])
        ->with(['qrpDetail' => function($q) {
            $q->select('id', 'daily_check_id', 'description', 'category_id', 'due_date', 'qrp_status_id')
            ->with(['category' => fn($q) => $q->select('id', 'category_name') ])
            ->with(['qrpStatus' => fn($q) => $q->select('id', 'name') ]);
        }])
        ->get();

        return response()->json([
            'datas' => $reports
        ]);
    }
}
