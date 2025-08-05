<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Position;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Excel;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::select('id', 'name', 'nip', 'email', 'cost_center_id', 'department_id', 'role_id', 'position_id', 'plant_id', 'leader_id')
                ->with(['costCenter' => function ($q) {
                    $q->select('id', 'cost_center_name');
                }])
                ->with(['department' => function ($q) {
                    $q->select('id', 'department_name');
                }])
                ->with(['role' => function ($q) {
                    $q->select('id', 'role_name');
                }])
                ->with(['position' => function ($q) {
                    $q->select('id', 'position_name');
                }])
                ->with(['plant' => function ($q) {
                    $q->select('id', 'plant_name');
                }])
                ->with(['leader' => function ($q) {
                    $q->select('id', 'name', 'nip');
                }]);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('costCenter', function ($row) {
                    if ($row->costCenter) {
                        return substr($row->costCenter->cost_center_name, 0, 3);
                    } else {
                        return '';
                    }
                })
                ->filterColumn('costCenter', function ($query, $keyword) {
                    $query->whereHas('costCenter', function ($q) use ($keyword) {
                        $q->where('cost_center_name', 'like', "%{$keyword}%");
                    });
                })
                ->addColumn('department', fn($row) => $row->department?->department_name ?? '')
                ->filterColumn('department', function ($query, $keyword) {
                    $query->whereHas('department', function ($q) use ($keyword) {
                        $q->where('department_name', 'like', "%{$keyword}%");
                    });
                })
                ->addColumn('role', fn($row) => $row->role?->role_name ?? '')
                ->filterColumn('role', function ($query, $keyword) {
                    $query->whereHas('role', function ($q) use ($keyword) {
                        $q->where('role_name', 'like', "%{$keyword}%");
                    });
                })
                ->addColumn('position', fn($row) => $row->position?->position_name ?? '')
                ->filterColumn('position', function ($query, $keyword) {
                    $query->whereHas('position', function ($q) use ($keyword) {
                        $q->where('position_name', 'like', "%{$keyword}%");
                    });
                })
                ->addColumn('plant', fn($row) => $row->plant?->plant_name ?? '')
                ->filterColumn('plant', function ($query, $keyword) {
                    $query->whereHas('plant', function ($q) use ($keyword) {
                        $q->where('plant_name', 'like', "%{$keyword}%");
                    });
                })
                ->addColumn('leader', function ($row) {
                    if ($row->leader) {
                        return $row->leader->name . ' (' . $row->leader->nip . ')';
                    } else {
                        return '';
                    }
                })
                ->filterColumn('leader', function ($query, $keyword) {
                    $query->whereHas('leader', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%")->orWhere('nip', 'like', "%{$keyword}%");
                    });
                })
                ->addColumn('action', function ($row) {
                    return '<a href="/admin/users/' . encrypt($row->id) . '/edit" class="text-warning fs-4"><i class="ti ti-edit"></i></a>';
                })
                ->rawColumns(['costCenter', 'department', 'role', 'position', 'plant', 'leader', 'action'])

                ->make(true);
        }
        return view('admin.users.index');
    }

    public function create()
    {
        $departments = Department::orderBy('department_name')->get();
        $roles = Role::when(Auth::user()->role_id == 2, function ($q) {
            $q->whereIn('id', [2, 3]);
        })->get();
        $positions = Position::get();
        return view('admin.users.create', compact('departments', 'roles', 'positions'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'nullable|email',
            'nip' => 'required|string|unique:users,nip',
            'role' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'nip' => $request->nip,
                'department_id' => $request->department,
                'cost_center_id' => $request->costCenter,
                'role_id' => $request->role,
                'position_id' => $request->position,
                'plant_id' => $request->plant,
                'leader_id' => $request->leader,
                'password' => bcrypt('password')
            ]);

            DB::commit();
            session()->flash('success', 'User berhasil ditambahkan');
            return redirect()->route('admin.users.index');

        } catch (\Throwable $error) {
            DB::rollBack();
            session()->flash('error', $error->getMessage());
            return redirect()->route('admin.users.index');
        }
    }

    public function edit($id)
    {
        $id = decrypt($id);
        $user = User::find($id);
        $roles = Role::get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'nullable|email',
            'nip' => 'required|string|unique:users,nip,' . $id,
            'role' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {

            User::where('id', $id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'nip' => $request->nip,
                'department_id' => $request->department,
                'cost_center_id' => $request->costCenter,
                'role_id' => $request->role,
                'position_id' => $request->position,
                'plant_id' => $request->plant,
                'leader_id' => $request->leader,
            ]);

            DB::commit();
            session()->flash('success', 'User berhasil diupdate');
            return redirect()->route('admin.users.index');
        } catch (\Throwable $error) {
            DB::rollBack();
            session()->flash('error', $error->getMessage());
            return redirect()->route('admin.users.edit', encrypt($id));
        }
    }

    public function destroy($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();

        try {
            User::where('id', $id)->delete();

            DB::commit();
            session()->flash('success', 'User berhasil dihapus');
            return redirect()->route('admin.users.index');
        } catch (\Throwable $error) {
            DB::rollBack();
            session()->flash('error', $error->getMessage());
            return redirect()->route('admin.users.index');
        }
    }

    
    
    
}
