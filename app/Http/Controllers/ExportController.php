<?php

namespace App\Http\Controllers;

use App\Exports\SafetyComiteeExport;
use App\Exports\UsersExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function userExport(Request $request)
    {
        dd($request->key);
        return Excel::download(new UsersExport($request), 'users.xlsx');
    }
}
