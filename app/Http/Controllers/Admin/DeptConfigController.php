<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminDepartment;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class DeptConfigController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $datas = User::select('id', 'name', 'nip')->where('role_id', 2);

            return DataTables::of($datas)
                ->addIndexColumn()
                ->addColumn('admin', fn($q) => $q->name . ' (' . $q->nip . ')')
                ->addColumn('department', function($q){
                    if (count($q->adminDepts) > 0) {
                        $html = '';
                        foreach ($q->adminDepts as $adminDept) {
                            $html .= '<span class="badge bg-success m-1 rounded-pill">'.$adminDept->department->department_name.'</span>';
                        }
                        return $html;
                    }
                })
                ->filterColumn('admin', function($q, $k){
                    $q->where('name', 'like', "%$k%")->orWhere('nip', 'like', "%$k%");
                })
                ->filterColumn('department', function ($query, $keyword) {
                    $query->orWhere(function($query)use($keyword){
                        $query->whereHas('adminDepts.department', function ($q) use ($keyword) {
                            $q->where('department_name', 'like', "%{$keyword}%");
                        });
                    });
                })
                ->addColumn('action',  fn($data) => '<a href="/admin/dept-config/' . encrypt($data->id) . '/edit" class="btn btn-sm btn-warning rounded" title="Edit"><i class="ti ti-edit"></i></a>')
                ->rawColumns(['department', 'action'])
                ->make(true);
        }
        return view('admin.dept-config.index');
    }

    public function create(Request $request)
    {
        if ($request->ajax()) {
            $datas = User::select('id', 'name', 'nip')
                ->whereIn('role_id', [1, 2, 4])
                ->whereNotIn('id', function ($q) {
                    $q->select('admin_id')->from('admin_departments');
                });

            return DataTables::of($datas)
                ->addIndexColumn()
                ->addColumn('action',  fn($data) => '
                    <form method="POST" action="/admin/dept-config">
                        ' . csrf_field() . '
                        <input type="hidden" name="user_id" value="' . encrypt($data->id) . '">
                        <button class="btn btn-success" type="submit">Tambah</button>
                    </form>
                ')
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.dept-config.create');
    }

    public function store(Request $request)
    {
        try {
            $user_id = decrypt($request->user_id);

            AdminDepartment::create([
                'admin_id' => $user_id
            ]);

            Session::flash('success', 'Data berhasil disimpan');
            return redirect()->route('admin.dept-config.index');
        } catch (\Throwable $th) {
            Session::flash('error', 'Gagal menyimpan data');
            Log::error($th->getMessage());
            return back();
        }
    }

    public function edit($id)
    {
        $user = User::find(decrypt($id));
        return view('admin.dept-config.edit', compact('user'));
    }

    public function masterDepartemenTable($admin)
    {
        $adminDepts = AdminDepartment::where('admin_id', $admin)->pluck('department_id');

        $datas = Department::when(count($adminDepts) > 0, function($q)use($adminDepts){
            $q->whereNotIn('id', $adminDepts);
        });

        return DataTables::of($datas)
            ->addIndexColumn()
            ->addColumn('action',  fn($data) => '
                    <form method="POST" action="/admin/master-departemen-table">
                        ' . csrf_field() . '
                        <input type="hidden" name="admin_id" value="' . encrypt($admin) . '">
                        <input type="hidden" name="department_id" value="' . encrypt($data->id) . '">
                        <button class="btn btn-sm btn-success rounded" type="submit" title="Tambah"><i class="ti ti-plus rounded"></i></button>
                    </form>
                ')
            ->rawColumns(['action'])
            ->make(true);
    }

    public function postMasterDepartemenTable(Request $request)
    {
        $admin = decrypt($request->admin_id);
        $department = decrypt($request->department_id);

        try {
            $cek = AdminDepartment::create([
                'admin_id' => $admin,
                'department_id' => $department,
            ]);

            Session::flash('success', 'Data berhasil disimpan');
            return redirect()->route('admin.dept-config.edit', encrypt($admin));
            
        } catch (\Throwable $th) {
            Session::flash('error', 'Data gagal disimpan');
            Log::error($th->getMessage());
            return back();
        }   
    }

    public function aksesDepartemenTable($admin)
    {
        $datas = AdminDepartment::where('admin_id', $admin);

        return DataTables::of($datas)
            ->addIndexColumn()
            ->addColumn('department', fn($data) => $data->department?->department_name)
            ->filterColumn('department', fn($data, $k) => $data->whereHas('department', function ($q) use ($k) {
                $q->where('department_name', 'like', "%$k%");
            }))
            ->addColumn('action',  fn($data) => '
                    <form method="POST" action="/admin/akses-departemen-table">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <input type="hidden" name="id" value="' . encrypt($data->id) . '">
                        <input type="hidden" name="admin_id" value="' . encrypt($admin) . '">
                        <button class="btn btn-sm btn-danger rounded" type="submit" title="Hapus"><i class="ti ti-trash rounded"></i></button>
                    </form>
                ')
            ->rawColumns(['department', 'action'])
            ->make(true);
    }

    public function destroyAksesDepartemenTable(Request $request)
    {
        $id = decrypt($request->id);
        $admin_id = decrypt($request->admin_id);

        try {
            AdminDepartment::where('id', $id)->delete();

            Session::flash('success', 'Data berhasil dihapus');
            return redirect()->route('admin.dept-config.edit', encrypt($admin_id));
            
        } catch (\Throwable $th) {
            Session::flash('error', 'Data gagal dihapus');
            Log::error($th->getMessage());
            return back();
        }   
    }
}
