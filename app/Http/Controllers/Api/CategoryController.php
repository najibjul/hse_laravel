<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $datas = Category::select('id', 'category_name')->get();
        return response()->json([
            'data' => $datas
        ]);
    }
}
