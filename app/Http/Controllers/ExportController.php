<?php

namespace App\Http\Controllers;

use App\Exports\SafetyComiteeExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function store()
    {
        return Excel::download(new SafetyComiteeExport, 'Safety_comitee.xlsx');
    }
}
