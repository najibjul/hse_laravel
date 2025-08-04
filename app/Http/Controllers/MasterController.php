<?php

namespace App\Http\Controllers;

use App\Models\CostCenter;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Http\Request;

class MasterController extends Controller
{
    public function department(Request $request)
    {
        $term = $request->q;

        if ($term) {
            $departments = Department::where('department_name', 'like', "%$term%")
                ->select('id', 'department_name')
                ->limit(10)
                ->get();
        } else {
            $departments = Department::select('id', 'department_name')
                ->limit(10)
                ->get();
        }
        return response()->json($departments);
    }

    public function position(Request $request)
    {
        $term = $request->q;

        if ($term) {
            $positions = Position::where('position_name', 'like', "%$term%")
                ->select('id', 'position_name')
                ->limit(10)
                ->get();
        } else {
            $positions = Position::select('id', 'position_name')
                ->limit(10)
                ->get();
        }
        return response()->json($positions);
    }

    public function costCenter(Request $request)
    {
        $term = $request->q;

        if ($term) {
            $costCenters = CostCenter::where('cost_center_name', 'like', "%$term%")
                ->selectRaw('id, left(cost_center_name, 3)')
                ->limit(10)
                ->get();
        } else {
            $costCenters = CostCenter::selectRaw('id, left(cost_center_name, 3) as cost_center_name')
                ->limit(10)
                ->get();
        }
        return response()->json($costCenters);
    }
}
