<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rank;
use Illuminate\Http\Request;

class RankController extends Controller
{
    public function index()
    {
        $datas = Rank::select('id', 'rank_name', 'due_day', 'rank_description')->get();
        return response()->json([
            'data' => $datas
        ]);
    }
}
