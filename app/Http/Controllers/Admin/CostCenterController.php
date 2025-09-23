<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CostCenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class CostCenterController extends Controller
{
    public function index(Request $request) 
    {
        if ($request->ajax()) {
            $data = CostCenter::select('id', 'cost_center_name');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('costCenter', function($row) {
                    return substr($row->cost_center_name,0,3);
                })
                ->filterColumn('costCenter', function ($query, $keyword) {
                    $query->where('cost_center_name', 'like', "%{$keyword}%");
                })
                
                ->addColumn('action', function ($row) {
                    return '<a href="/admin/cost-centers/' . encrypt($row->id) . '/edit" class="text-warning fs-4"><i class="ti ti-edit"></i></a>';
                })
                ->rawColumns(['action', 'costCenter'])
                ->make(true);
        }
        return view('admin.cost-centers.index');
    }
    
    public function create() 
    {
        return view('admin.cost-centers.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'costCenter' => 'required|string|unique:cost_centers,cost_center_name'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            CostCenter::create([
                'cost_center_name' => $request->costCenter
            ]);

            DB::commit();
            session()->flash('success', 'Cost center berhasil disimpan');
            return redirect()->route('admin.cost-centers.index');

        } catch (\Throwable $error) {
            DB::rollBack();
            session()->flash('error', $error->getMessage());
            return redirect()->route('admin.cost-centers.create');
        }
    }

    public function edit($id)
    {
        $id = decrypt($id);
        $costCenter = CostCenter::find($id);
        return view('admin.cost-centers.edit', compact('costCenter'));
    }

    public function update($id, Request $request)
    {
        $id = decrypt($id);
        $validator = Validator::make($request->all(), [
            'costCenter' => 'required|string|unique:cost_centers,cost_center_name,' . $id . ',id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            CostCenter::where('id', $id)->update([
                'cost_center_name' => $request->costCenter,
            ]);

            DB::commit();
            session()->flash('success', 'Cost Center berhasil diupdate');
            return redirect()->route('admin.cost-centers.index');

        } catch (\Throwable $error) {
            DB::rollBack();
            session()->flash('error', $error->getMessage());
            return redirect()->route('admin.cost-centers.edit', encrypt($id));
        }
    }
}
