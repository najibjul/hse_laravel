<?php

namespace App\Http\Controllers;

use App\Models\CostCenter;
use App\Models\Department;
use App\Models\Plant;
use App\Models\Position;
use App\Models\User;
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

    public function departmentShow($id)
    {
        $department = Department::findOrFail($id);
        return response()->json(['id' => $department->id, 'department_name' => $department->department_name]);
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

    public function positionShow($id)
    {
        $position = Position::findOrFail($id);
        return response()->json(['id' => $position->id, 'position_name' => $position->position_name]);
    }

    public function costCenter(Request $request)
    {
        $term = $request->q;

        if ($term) {
            $costCenters = CostCenter::where('cost_center_name', 'like', "%$term%")
                ->selectRaw('id, left(cost_center_name, 3) as cost_center_name')
                ->limit(10)
                ->get();
        } else {
            $costCenters = CostCenter::selectRaw('id, left(cost_center_name, 3) as cost_center_name')
                ->limit(10)
                ->get();
        }
        return response()->json($costCenters);
    }

    public function costCenterShow($id)
    {
        $costCenter = CostCenter::findOrFail($id);
        return response()->json(['id' => $costCenter->id, 'cost_center_name' => substr($costCenter->cost_center_name, 0, 3)]);
    }

    public function plant(Request $request)
    {
        $term = $request->q;

        if ($term) {
            $plants = Plant::where('plant_name', 'like', "%$term%")
                ->select('id', 'plant_name')
                ->limit(10)
                ->get();
        } else {
            $plants = Plant::select('id', 'plant_name')
                ->limit(10)
                ->get();
        }
        return response()->json($plants);
    }

    public function plantShow($id)
    {
        $plant = Plant::findOrFail($id);
        return response()->json(['id' => $plant->id, 'plant_name' => $plant->plant_name]);
    }

    public function leader(Request $request)
    {
        $term = $request->q;

        if ($term) {
            $users = User::where('name', 'like', "%$term%")
                ->orWhere('nip', 'like', "%$term%")
                ->select('id', 'name', 'nip')
                ->limit(10)
                ->get();
        } else {
            $users = User::select('id', 'name', 'nip')
                ->limit(10)
                ->get();
        }
        return response()->json($users);
    }

    public function leaderShow($id)
    {
        $user = User::findOrFail($id);
        return response()->json(['id' => $user->id, 'name' => $user->name . " (" . $user->nip . ")"]);
    }
}
