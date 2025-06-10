<?php

namespace App\Http\Controllers\Admin;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DepartmentController
{
    public function index()
    {
        $departments = Department::get();

        return view('admin.departments.index', compact('departments'));
    }

    public function create()
    {
        return view('admin.departments.create');
    }

    public function store(Request $request)
    {
        $user = User::where('nip', $request->department_head)->first();

        $validator = Validator::make($request->all(), [
            'department_name' => 'required|string|unique:departments,department_name'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $department = Department::create([
                'department_name' => $request->department_name
            ]);

            DB::commit();
            session()->flash('success', 'Department berhasil disimpan');
            return redirect()->route('admin.departments.index');
        } catch (\Throwable $error) {
            DB::rollBack();
            session()->flash('error', $error->getMessage());
            return redirect()->route('admin.departments.create');
        }
    }

    public function edit($id)
    {
        $id = decrypt($id);
        $department = Department::find($id);
        return view('admin.departments.edit', compact('department'));
    }

    public function searchDh($id, Request $request)
    {
        if ($request->has('q')) {
            $search = $request->q;
        } else {
            $search = null;
        }

        $users = User::where('department_id', $id)
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')->orWhere('nip', 'like', '%' . $search . '%');
                });
            })
            ->select('id', 'name', 'nip')
            ->limit(10)->get();

        return response()->json($users);
    }

    public function update($id, Request $request)
    {
        $id = decrypt($id);
        $validator = Validator::make($request->all(), [
            'department_name' => 'required|string|unique:departments,department_name,' . $id . ',id',
            'department_head' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            Department::where('id', $id)->update([
                'department_name' => $request->department_name,
                'dept_head_id' => $request->department_head
            ]);

            DB::commit();
            session()->flash('success', 'Department berhasil diupdate');
            return redirect()->route('admin.departments.index');

        } catch (\Throwable $error) {
            DB::rollBack();
            session()->flash('error', $error->getMessage());
            return redirect()->route('admin.departments.edit', encrypt($id));
        }
    }
}
