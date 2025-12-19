<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class PlantController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Plant::select('id', 'plant_name');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<a href="/admin/plants/' . encrypt($row->id) . '/edit" class="btn btn-warning btn-sm"><i class="ti ti-edit"></i></a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.plants.index');
    }

    public function create()
    {
        return view('admin.plants.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plant' => 'required|string|unique:plants,plant_name'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            Plant::create([
                'plant_name' => $request->plant
            ]);

            DB::commit();
            session()->flash('success', 'Plant berhasil disimpan');
            return redirect()->route('admin.plants.index');

        } catch (\Throwable $error) {
            DB::rollBack();
            session()->flash('error', $error->getMessage());
            return redirect()->route('admin.plants.create');
        }
    }

    public function edit($id)
    {
        $id = decrypt($id);
        $plant = Plant::find($id);
        return view('admin.plants.edit', compact('plant'));
    }

    public function update($id, Request $request)
    {
        $id = decrypt($id);
        $validator = Validator::make($request->all(), [
            'plant' => 'required|string|unique:plants,plant_name,' . $id . ',id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            Plant::where('id', $id)->update([
                'plant_name' => $request->plant,
            ]);

            DB::commit();
            session()->flash('success', 'Plant berhasil diupdate');
            return redirect()->route('admin.plants.index');

        } catch (\Throwable $error) {
            DB::rollBack();
            session()->flash('error', $error->getMessage());
            return redirect()->route('admin.plants.edit', encrypt($id));
        }
    }
}
