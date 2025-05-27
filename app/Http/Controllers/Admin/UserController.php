<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request) 
    {   
        if ($request->search) {
            $search = $request->search;
        } else {
            $search = null;
        }

        $users = User::when($search != null, function($q) use($search){
            $q->where('name','like', '%' . $search . '%')
            ->orWhere('nip', 'like', '%' . $search . '%')
            ->orWhere('email', 'like', '%' . $search . '%')
            ->orWhereHas('department', function($q)use($search){
                $q->where('department_name', 'like', '%' . $search . '%');
            })
            ->orWhereHas('role', function($q)use($search){
                $q->where('role_name', 'like', '%' . $search . '%');
            });
        })->paginate(10);
        
        return view('admin.users.index', compact('users','search'));
    }

    public function create() 
    {
        $departments = Department::orderBy('department_name')->get();
        $roles = Role::when(Auth::user()->role_id == 2, function($q){
            $q->whereIn('id', [2,3]);
        })->get();
        return view('admin.users.create', compact('departments', 'roles'));
    }

    public function store(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'nip' => 'required|string|unique:users,nip',
            'department' => 'required',
            'role' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'nip' => $request->nip,
                'department_id' => $request->department,
                'role_id' => $request->role,
                'password' => bcrypt('password'),
            ]);

            DB::commit();
            session()->flash('success', 'User berhasil disimpan');
            return redirect()->route('admin.users.index');

        } catch (\Throwable $error) {
            DB::rollBack();
            session()->flash('error', $error->getMessage());
            return redirect()->route('admin.users.create');
        }
    }

    public function edit ($id) 
    {
        $id = decrypt($id);

        $user = User::find($id);
        $departments = Department::orderBy('department_name')->get();
        $roles = Role::when(Auth::user()->role_id == 2, function($q){
            $q->whereIn('id', [2,3]);
        })->get();
        return view('admin.users.edit', compact('user', 'departments', 'roles'));
    }

    public function update($id, Request $request) 
    {
        $id = decrypt($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,'. $id,
            'nip' => 'required|string|unique:users,nip,'. $id,
            'department' => 'required',
            'role' => 'required',
            'password' => 'nullable|min:8'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {

            if ($request->password == "") {
                User::where('id', $id)->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'nip' => $request->nip,
                    'department_id' => $request->department,
                    'role_id' => $request->role
                ]);
            } else {
                User::where('id', $id)->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'nip' => $request->nip,
                    'department_id' => $request->department,
                    'role_id' => $request->role,
                    'password' => bcrypt($request->password)
                ]);
            }

            DB::commit();
            session()->flash('success', 'User berhasil diupdate');
            return redirect()->route('admin.users.index');

        } catch (\Throwable $error) {
            DB::rollBack();
            session()->flash('error', $error->getMessage());
            return redirect()->route('admin.users.edit', $id);
        }
    }

    public function destroy ($id, Request $request) 
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
