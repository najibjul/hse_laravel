<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DailyCheck;
use App\Models\Notification;
use App\Models\QrpApproval;
use App\Models\QrpDetail;
use App\Models\QrpRecomendation;
use App\Models\Rank;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiQrpController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user' => 'required|integer',
            'area' => 'required',
            'factor' => 'required|integer',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'recomendation' => 'required',
            'category' => 'required|integer',
            'rank' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        $user = User::find($request->user);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('image', $filename, 'public');
        }

        $dailyCheck = DailyCheck::create([
            'user_id' => $user->id,
            'area' => $request->area,
            'factor_id' => $request->factor,
            'check_status' => 'NG',
            'cost_center_id' => $user->cost_center_id,
            'department_id' => $user->department_id,
            'plant_id' => $user->plant_id,
            'position_id' => $user->position_id
        ]);

        $rank = Rank::find($request->rank);
        $due_date = Carbon::now()->addDays($rank->due_day);

        $qrpDetail = QrpDetail::create([
            'daily_check_id' => $dailyCheck->id,
            'department_id' => $user->department_id,
            'description' => $request->description,
            'category_id' => $request->category,
            'before' => $filename,
            'rank_id' => $request->rank,
            'due_date' =>  $due_date,
            'qrp_status_id' => 1,
        ]);

        QrpApproval::create([
            'qrp_detail_id' => $qrpDetail->id,
            'approval_id' => $user->leader_id,
            'status' => 'waiting'
        ]);

        QrpRecomendation::create([
            'qrp_detail_id' => $qrpDetail->id,
            'user_id' => $user->id,
            'recomendation' => $request->recomendation,
            'status' => 1
        ]);

        Notification::create([
            'user_id' => $user->leader_id,
            'title' => 'Approve safety comitee',
            'body' => 'Anda memiliki tugas untuk approve safety comitee',
            'target' => 'qrp',
            'target_id' => $dailyCheck->id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan',
            'data' => [
                'url' => asset('storage/' . $path),
                'filename' => $filename
            ]
        ], 201);
    }
}
