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
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request) 
    {   
        $users = User::get();
        
        return view('admin.users.index', compact('users'));
    }

    public function create() 
    {
        $departments = Department::orderBy('department_name')->get();
        $roles = Role::when(Auth::user()->role_id == 2, function($q){
            $q->whereIn('id', [2,3]);
        })->get();
        $positions = Position::get();
        return view('admin.users.create', compact('departments', 'roles', 'positions'));
    }

    public function store(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'nip' => 'required|string|unique:users,nip',
            'department' => 'required',
            'role' => 'required',
            'position' => 'required'
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
                'role_id' => $request->role,
                'position_id' => $request->position,
                'password' => bcrypt('password')
            ]);

            DB::commit();
            session()->flash('success', 'User berhasil ditambahkan');
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
        $positions = Position::get();

        return view('admin.users.edit', compact('user', 'departments', 'roles', 'positions'));
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
            'password' => 'nullable|min:8',
            'position' => 'required'
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
                    'role_id' => $request->role,
                    'position_id' => $request->position
                ]);
            } else {
                User::where('id', $id)->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'nip' => $request->nip,
                    'department_id' => $request->department,
                    'role_id' => $request->role,
                    'position_id' => $request->position,
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

    public function destroy ($id) 
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
