<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Position;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
        $search = $request->input('search.value', '');
        $user = Auth::user();

        $data = User::query()
            ->select('id', 'name', 'nip', 'email', 'cost_center_id', 'department_id', 'role_id', 'position_id', 'plant_id', 'leader_id')
            ->with([
                'costCenter:id,cost_center_name',
                'department:id,department_name',
                'role:id,role_name',
                'position:id,position_name',
                'plant:id,plant_name',
                'leader:id,name,nip',
            ])
            ->when($user->role_id == 2, fn($q) =>
                $q->whereHas('department', fn($d) =>
                    $d->whereIn('department_id', $user->adminDepts->pluck('department_id'))
                )
            )
            ->when($search, fn($q) =>
                $q->where(function($qq) use ($search) {
                    $qq->where('name', 'like', "%{$search}%")
                       ->orWhere('nip', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%")
                       ->orWhereHas('costCenter', fn($r) => $r->where('cost_center_name', 'like', "%{$search}%"))
                       ->orWhereHas('department', fn($r) => $r->where('department_name', 'like', "%{$search}%"))
                       ->orWhereHas('role', fn($r) => $r->where('role_name', 'like', "%{$search}%"))
                       ->orWhereHas('position', fn($r) => $r->where('position_name', 'like', "%{$search}%"))
                       ->orWhereHas('plant', fn($r) => $r->where('plant_name', 'like', "%{$search}%"))
                       ->orWhereHas('leader', fn($r) => $r
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('nip', 'like', "%{$search}%"));
                })
            );

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('costCenter', fn($row) => substr($row->costCenter?->cost_center_name ?? '', 0, 3))
            ->addColumn('department', fn($row) => $row->department?->department_name ?? '')
            ->addColumn('role', fn($row) => $row->role?->role_name ?? '')
            ->addColumn('position', fn($row) => $row->position?->position_name ?? '')
            ->addColumn('plant', fn($row) => $row->plant?->plant_name ?? '')
            ->addColumn('leader', fn($row) => $row->leader ? "{$row->leader->name} ({$row->leader->nip})" : '')
            ->addColumn('action', fn($row) =>
                '<div class="d-flex gap-2">
                    <a href="/admin/users/' . encrypt($row->id) . '/edit" class="btn btn-sm btn-warning"><i class="ti ti-edit"></i></a>
                    <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#trash'.$row->id.'"><i class="ti ti-trash"></i></a>
                </div>

                <div class="modal fade" id="trash'.$row->id.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Hapus user</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="post" action="'.route('admin.users.destroy', $row->id).'">
                        '.csrf_field().'
                        '. method_field('delete') .'
                        <div class="modal-body">
                            Hapus user <b><i>'.$row->name.' ('. $row->nip .') </i></b>?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                            <button type="submit" class="btn btn-danger">Ya</button>
                        </div>
                        </div>
                    </div>
                </div>')
            ->rawColumns(['action'])
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
        User::where('id', $id)->delete();

        session()->flash('success', 'User berhasil dihapus');
        return redirect()->route('admin.users.index');
    }

    public function resetPassword($id)
    {
        DB::table('sessions')->where('user_id', $id)->delete();
        User::where('id', $id)->update([
            'password' => Hash::make('P@ssw0rd'),
            'remember_token' => null,
            'google2fa_secret' => null,
            'must_change_password' => 0,
            'password_expire_at' => null
        ]);

        session()->flash('success', 'Kata sandi berhasil diupdate');
        return redirect()->route('admin.users.index');
    }

}
