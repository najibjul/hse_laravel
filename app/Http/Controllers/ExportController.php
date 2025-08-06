<?php

namespace App\Http\Controllers;

use App\Exports\CostCenterExport;
use App\Exports\DepartmentExport;
use App\Exports\PlantExport;
use App\Exports\PositionExport;
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
}
