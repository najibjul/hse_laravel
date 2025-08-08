<?php

namespace App\Http\Controllers;

use App\Exports\CostCenterExport;
use App\Exports\DepartmentExport;
use App\Exports\PlantExport;
use App\Exports\PositionExport;
use App\Exports\QrpExport;
use App\Exports\SafetyComiteeExport;
use App\Exports\UsersExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function userExport(Request $request)
    {
        $param = $request->param;        
        return Excel::download(new UsersExport($param), 'users-exported-at-'.now().'.xlsx');
    }

    public function departmentExport(Request $request)
    {
        $param = $request->param;        
        return Excel::download(new DepartmentExport($param), 'departments-exported-at-'.now().'.xlsx');
    }

    public function costCenterExport(Request $request)
    {
        $param = $request->param;        
        return Excel::download(new CostCenterExport($param), 'cost-centers-exported-at-'.now().'.xlsx');
    }

    public function positionExport(Request $request)
    {
        $param = $request->param;        
        return Excel::download(new PositionExport($param), 'positions-exported-at-'.now().'.xlsx');
    }

    public function plantExport(Request $request)
    {
        $param = $request->param;        
        return Excel::download(new PlantExport($param), 'plants-exported-at-'.now().'.xlsx');
    }

    public function qrpExport(Request $request)
    {
        $request->validate([
            'param' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $param = $request->param;        
        $start_date = $request->start_date;        
        $end_date = $request->end_date;        
        return Excel::download(new QrpExport($param, $start_date, $end_date), 'safety-comitee-exported-at-'.now().'.xlsx');
    }
}
