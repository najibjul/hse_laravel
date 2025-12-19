<?php

namespace App\Http\Controllers\Admin;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class DepartmentController
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->input('search.value', '');

            $data = Department::select('id', 'department_name')
                ->when(Auth::user()->role_id == 2, function($q){
                    $q->whereIn('id', Auth::user()->adminDepts->pluck('department_id'));
                })
                ->when($search, fn($q) => $q->where('department_name', 'like', "%{$search}%"));

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<a href="/admin/departments/' . encrypt($row->id) . '/edit" class="btn btn-sm btn-warning"><i class="ti ti-edit"></i></a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.departments.index');
    }

    public function create()
    {
        return view('admin.departments.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'department' => 'required|string|unique:departments,department_name'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            Department::create([
                'department_name' => $request->department
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

    public function update($id, Request $request)
    {
        $id = decrypt($id);
        $validator = Validator::make($request->all(), [
            'department' => 'required|string|unique:departments,department_name,' . $id . ',id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            Department::where('id', $id)->update([
                'department_name' => $request->department,
            ]);

            DB::commit();
            session()->flash('success', 'Departemen berhasil diupdate');
            return redirect()->route('admin.departments.index');

        } catch (\Throwable $error) {
            DB::rollBack();
            session()->flash('error', $error->getMessage());
            return redirect()->route('admin.departments.edit', encrypt($id));
        }
    }
}
