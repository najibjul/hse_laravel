<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class PositionController extends Controller
{
    public function index(Request $request) 
    {
        if ($request->ajax()) {

            $isQrpEnabled = $request->isQrpEnabled;

            $data = Position::when(isset($isQrpEnabled), function($q)use($isQrpEnabled){ 
                return $q->where('is_qrp_enabled', $isQrpEnabled); 
            })->select('id', 'position_name', 'is_qrp_enabled');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('safety_comitee', function ($row) {
                    if ($row->is_qrp_enabled) {
                        return '<span class="badge bg-success rounded-pill">Ya</span>';
                    } else {
                        return '<span class="badge bg-danger rounded-pill">Tidak</span>';
                    }   
                })
                ->addColumn('action', function ($row) {
                    return '<a href="/admin/positions/' . encrypt($row->id) . '/edit" class="text-warning fs-4"><i class="ti ti-edit"></i></a>';
                })
                ->rawColumns(['safety_comitee', 'action' ])
                ->make(true);
        }
        return view('admin.positions.index');
    }

    public function create()
    {
        return view('admin.positions.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'position' => 'required|string|unique:positions,position_name',
            'isQrpEnabled' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            Position::create([
                'position_name' => $request->position,
                'is_qrp_enabled' => $request->isQrpEnabled
            ]);

            DB::commit();
            session()->flash('success', 'Position berhasil disimpan');
            return redirect()->route('admin.positions.index');

        } catch (\Throwable $error) {
            DB::rollBack();
            session()->flash('error', $error->getMessage());
            return redirect()->route('admin.positions.create');
        }
    }
    
    public function edit($id)
    {
        $id = decrypt($id);
        $position = Position::find($id);
        return view('admin.positions.edit', compact('position'));
    }

    public function update($id, Request $request)
    {
        $id = decrypt($id);
        $validator = Validator::make($request->all(), [
            'position' => 'required|string|unique:positions,position_name,' . $id . ',id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            Position::where('id', $id)->update([
                'position_name' => $request->position,
                'is_qrp_enabled' => $request->isQrpEnabled
            ]);

            DB::commit();
            session()->flash('success', 'Position berhasil diupdate');
            return redirect()->route('admin.positions.index');

        } catch (\Throwable $error) {
            DB::rollBack();
            session()->flash('error', $error->getMessage());
            return redirect()->route('admin.positions.edit', encrypt($id));
        }
    }
}
