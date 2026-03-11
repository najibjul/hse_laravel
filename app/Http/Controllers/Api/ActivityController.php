<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DailyCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ActivityController extends Controller
{
    public function post(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'activity' => 'required',
            'area' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message'  => $validator->errors()->first()
            ], 422);
        }

        try {
            $data = DailyCheck::create([
                'user_id' => $request->user_id,
                'activity' => $request->activity,
                'area' => $request->area,
                'status' => 'OK'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message'  => 'Error detected!'
            ], 422);
        }

        return response()->json([
            'message' => 'Post created',
            'data' => $data
        ], 201);
    }
}
