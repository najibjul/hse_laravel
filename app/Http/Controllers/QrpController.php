<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\DailyCheck;
use App\Models\Factor;
use App\Models\Notification;
use App\Models\QrpApproval;
use App\Models\QrpDetail;
use App\Models\QrpRecomendation;
use App\Models\QrpStatus;
use App\Models\Rank;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Jenssegers\Agent\Agent;
use Yajra\DataTables\DataTables;

class QrpController extends Controller
{
    public function dailyChecking(Request $request)
    {
        if ($request->search) {
            $search = $request->search;
        } else {
            $search = null;
        }

        $agent = new Agent();

        session()->put('factor', 1);

        $factors = [];

        if ($agent->isMobile()) {

            $check_status = $request->check_status;
            $safety_comitee_status = $request->safety_comitee_status;
            $start_date = $request->start_date;
            $end_date = $request->end_date;

            $dailyChecks = DailyCheck::when(Auth::user()->role_id == 3, function ($q) {
                $q->where('user_id', Auth::user()->id)
                    ->when(Auth::user()->position_id == 2, function ($q) {
                        $q->orWherehas('user', function ($q) {
                            $q->where('department_id', Auth::user()->department_id);
                        });
                    });
            })
                ->when($search, function ($q) use ($search) {
                    $q->where(function ($q) use ($search) {
                        $q->whereHas('user', function ($q) use ($search) {
                            $q->where('name', 'like', '%' . $search . '%')
                                ->orwhere('nip', 'like', '%' . $search . '%');
                        })
                            ->orWhere(function ($q) use ($search) {
                                $q->where('activity', 'like', '%' . $search . '%')
                                    ->orWhereHas('qrpDetail', function ($q) use ($search) {
                                        $q->where('description', 'like', '%' . $search . '%');
                                    });
                            })
                            ->orWhere('area', 'like', '%' . $search . '%')
                            ->orWhere('check_status', 'like', '%' . $search . '%')
                            ->orWhereHas('qrpDetail', function ($q) use ($search) {
                                $q->whereHas('qrpStatus', function ($q) use ($search) {
                                    $q->where('name', 'like', '%' . $search . '%');
                                });
                            });
                    });
                })
                ->when(isset($check_status) && !empty($check_status), function($q) use($check_status){
                    $q->where('check_status', $check_status);
                })
                ->when(isset($safety_comitee_status) && !empty($safety_comitee_status), function($q) use($safety_comitee_status){
                    $q->whereHas('qrpDetail', function($q) use($safety_comitee_status){
                        $q->where('qrp_status_id', $safety_comitee_status);
                    });
                })
                ->when(isset($start_date) && !empty($start_date) && isset($end_date) && !empty($end_date), function($q) use($start_date, $end_date){
                    $start_date = Carbon::parse($start_date)->startOfDay();
                    $end_date = Carbon::parse($end_date)->endOfDay();
                    $q->whereBetween('created_at', [$start_date, $end_date]);
                })
                ->whereHas('user')
                ->latest()
                ->paginate(20);

                $statuses = QrpStatus::select('id', 'name')->get();

        } else {
            $factors = Factor::select('id', 'factor_name')->get();
            $statuses = QrpStatus::select('id', 'name')->get();

            $dailyChecks = null;

            if ($request->ajax()) {

                $cari_user = $request->cari_user;
                $cari_aktifitas = $request->cari_aktifitas;
                $cari_area = $request->cari_area;
                $start_date = $request->start_date;
                $end_date = $request->end_date;
                $cari_faktor = $request->cari_faktor;
                $cari_cek = $request->cari_cek;
                $cari_status = $request->cari_status;

                $data = DailyCheck::with(['qrpDetail' => function ($q) {
                    $q->select('daily_check_id', 'description', 'qrp_status_id')
                        ->with(['qrpStatus' => function ($q) {
                            $q->select('id', 'name', 'class');
                        }]);
                }])->with(['user' => function ($q) {
                    $q->select('id', 'name', 'nip');
                }])->with(['factor' => function ($q) {
                    $q->select('id', 'factor_name');
                }])
                ->whereHas('user')
                ->when(Auth::user()->role_id == 3, function ($q) {
                    $user_id = Auth::user()->id;
                    $user_ids[] = $user_id;
                    $team_ids = [$user_id];

                    for ($i = 0; $i < User::count(); $i++) {
                        $teams = User::whereIn('leader_id', $user_ids)->select('id')->get();
                        if (count($teams) == 0) {
                            break;
                        } else {
                            $user_ids = $teams->pluck('id')->toArray();
                            $team_ids = array_merge($team_ids, $user_ids);
                        }
                    }

                    $q->whereIn('user_id', $team_ids);
                })

                ->when($cari_user, function($q) use ($cari_user){
                    $q->whereHas('user', function($q) use($cari_user) {
                        $q->where('name', 'like', "%$cari_user%")
                        ->orWhere('nip', 'like', "%$cari_user%");
                    });
                })

                ->when($cari_aktifitas, function($q) use ($cari_aktifitas){
                    $q->where('activity', 'like', "%$cari_aktifitas%")
                    ->orWhereHas('qrpDetail', function($q) use($cari_aktifitas){
                        $q->where('description', 'like', "%$cari_aktifitas%");
                    });
                })

                ->when($cari_area, function($q) use ($cari_area){
                    $q->where('area', 'like', "%$cari_area%");
                })

                ->when($start_date && $end_date, function($q) use ($start_date, $end_date){
                    $q->whereBetween('created_at', [$end_date, $start_date]);
                })

                ->when($cari_faktor, function($q) use ($cari_faktor){
                    $q->where('factor_id', $cari_faktor);
                })

                ->when($cari_faktor, function($q) use ($cari_faktor){
                    $q->where('factor_id', $cari_faktor);
                })

                ->when($cari_cek, function($q) use ($cari_cek){
                    $q->where('check_status', $cari_cek);
                })

                ->when($cari_status, function($q) use ($cari_status){
                    $q->wherehas('qrpDetail', function($q) use ($cari_status){
                        $q->where('qrp_status_id', $cari_status);
                    });
                })
                ->orderByDesc('id')
                ->select('id', 'user_id', 'activity', 'area', 'factor_id', 'check_status', 'created_at');

                // if ($request->start_date && $request->end_date) {
                //     $data->whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date);
                // }


                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('user', function ($row) {
                        return $row->user->name . ' (' . $row->user->nip . ')';
                    })
                    // ->filterColumn('user', function ($query, $keyword) {
                    //     $query->whereHas('user', function ($q) use ($keyword) {
                    //         $q->where('name', 'like', "%{$keyword}%")->orWhere('nip', 'like', "%{$keyword}%");
                    //     });
                    // })
                    ->addColumn('description', function ($row) {
                        return $row->qrpDetail?->description;
                    })
                    // ->filterColumn('description', function ($query, $keyword) {
                    //     $query->whereHas('qrpDetail', function ($q) use ($keyword) {
                    //         $q->where('description', 'like', "%{$keyword}%");
                    //     });
                    // })
                    ->addColumn('area', function ($row) {
                        return $row->area;
                    })
                    // ->filterColumn('area', function ($query, $keyword) {
                    //     $query->where('area', 'like', "%{$keyword}%");
                    // })
                    ->addColumn('created_at', function ($row) {
                        return Carbon::parse($row->created_at)->format('d M Y');
                    })
                    ->addColumn('factor', function ($row) {
                        return $row->factor?->factor_name;
                    })
                    // ->filterColumn('factor', function ($query, $keyword) {
                    //     $query->whereHas('factor', function ($q) use ($keyword) {
                    //         $q->where('factor_name', 'like', "%{$keyword}%");
                    //     });
                    // })
                    ->addColumn('check_status', function ($row) {
                        if ($row->check_status == "OK") {
                            return '<span class="badge bg-success rounded">OK</span>';
                        } else {
                            return '<span class="badge bg-danger rounded">NG</span>';
                        }
                    })
                    // ->filterColumn('check_status', function ($query, $keyword) {
                    //     $query->where('check_status', 'like', "%{$keyword}%");
                    // })
                    ->addColumn('status', function ($row) {
                        return '<span class="'.$row->qrpDetail?->qrpStatus?->class.'">'.$row->qrpDetail?->qrpStatus?->name.'</span>';
                    })
                    // ->filterColumn('status', function ($query, $keyword) {
                    //     $query->whereHas('qrpDetail', function ($q) use ($keyword) {
                    //         $q->whereHas('qrpStatus', function ($q) use ($keyword) {
                    //             $q->where('name', 'like', "%{$keyword}%");
                    //         });
                    //     });
                    // })
                    ->addColumn('action', function ($row) {
                        return '<a href="/qrp-form/detail/' . encrypt($row->id) . '" class="btn btn-sm btn-outline-info rounded"><i class="ti ti-eye"></i></a>';
                    })
                    ->rawColumns(['user', 'description', 'area', 'created_at', 'factor', 'check_status', 'status', 'action'])
                    ->make(true);
            }
        }

        return view('qrp.daily-checking', compact('dailyChecks', 'search', 'agent', 'factors', 'statuses'));
    }

    public function dailyCheckingPost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'activity' => 'required',
            'area' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        DB::beginTransaction();

        try {
            DailyCheck::create([
                'user_id' => Auth::user()->id,
                'activity' => $request->activity,
                'area' => $request->area,
                'status' => 'OK'
            ]);

            DB::commit();
            session()->flash('success', 'Pengecekan berhasil disimpan');
            return redirect()->route('qrp.daily-checking');
        } catch (\Exception $error) {
            DB::rollBack();

            session()->flash('error', $error->getMessage());
            return redirect()->route('qrp.daily-checking');
        }
    }

    public function changeFactor()
    {
        $factor = session('factor');

        if ($factor == 1) {
            session()->put('factor', 2);
        } elseif ($factor == 2) {
            session()->put('factor', 3);
        } elseif ($factor == 3) {
            session()->put('factor', 4);
        } elseif ($factor == 4) {
            session()->put('factor', 5);
        }
    }

    public function qrpForm()
    {
        $factor = Factor::find(session('factor'));
        $categories = Category::get();
        $ranks = Rank::get();
        $leader = User::find(Auth::user()->leader_id);
        $agent = new Agent();
        return view('qrp.qrp-form', compact('factor', 'categories', 'ranks', 'leader', 'agent'));
    }

    public function qrpFormPost(Request $request)
    {
        if ($request->dataUri != "") {
            $validator = Validator::make($request->all(), [
                'area' => 'required',
                'description' => 'required',
                'dataUri' => 'required',
                'recomendation' => 'required',
                'category' => 'required',
                'rank' => 'required',
                'leader' => 'required',

            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'area' => 'required',
                'description' => 'required',
                'galery' => 'required|image|mimes:jpeg,png,jpg|max:5120',
                'recomendation' => 'required',
                'category' => 'required',
                'rank' => 'required',
                'leader' => 'required',
            ]);
        }

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        try {
            if ($request->dataUri != "") {
                $dataUri = $request->dataUri;

                if (preg_match('/^data:image\/(\w+);base64,/', $dataUri, $type)) {
                    $data = substr($dataUri, strpos($dataUri, ',') + 1);
                    $ext = strtolower($type[1]);
                    $data = base64_decode($data);
                }

                $filename = time() . '.' . $ext;
                Storage::disk('public')->put("image/{$filename}", $data);
            } else {
                $file = $request->file('galery');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('image', $filename, 'public');
            }

            $dailyCheck = DailyCheck::create([
                'user_id' => Auth::user()->id,
                'area' => $request->area,
                'factor_id' => session('factor'),
                'check_status' => 'NG',
                'cost_center_id' => Auth::user()->cost_center_id,
                'department_id' => Auth::user()->department_id,
                'plant_id' => Auth::user()->plant_id,
                'position_id' => Auth::user()->position_id
            ]);

            $rank = Rank::find($request->rank);
            $due_date = Carbon::now()->addDays($rank->due_day);

            $qrpDetail = QrpDetail::create([
                'daily_check_id' => $dailyCheck->id,
                'department_id' => Auth::user()->department_id,
                'description' => $request->description,
                'category_id' => $request->category,
                'before' => $filename,
                'rank_id' => $request->rank,
                'due_date' =>  $due_date,
                'qrp_status_id' => 1,
            ]);

            QrpApproval::create([
                'qrp_detail_id' => $qrpDetail->id,
                'approval_id' => $request->leader,
                'status' => 'waiting'
            ]);

            QrpRecomendation::create([
                'qrp_detail_id' => $qrpDetail->id,
                'user_id' => Auth::user()->id,
                'recomendation' => $request->recomendation,
                'status' => 1
            ]);

            Notification::create([
                'user_id' => $request->leader,
                'title' => 'Approve safety comitee',
                'body' => 'Anda memiliki tugas untuk approve safety comitee',
                'target' => 'qrp',
                'target_id' => $dailyCheck->id,
            ]);

            DB::commit();

            session()->flash('success', 'Laporan berhasil dibuat');
            return redirect()->route('qrp.qrp-form-detail', encrypt($dailyCheck->id));
        } catch (\Exception $error) {
            DB::rollBack();
            session()->flash('error', $error->getMessage());
            return redirect()->route('qrp.qrp-form');
        }
    }

    public function qrpFormDetail($id)
    {
        $adhs = User::where('department_id', Auth::user()->department_id)->where('position_id', 3)->get();
        $agent = new Agent();
        $dailyCheck = DailyCheck::find(decrypt($id));

        return view('qrp.qrp-form-detail', compact('dailyCheck', 'agent'));
    }

    public function qrpFormDelete($id)
    {
        $dailyCheck = DailyCheck::find($id);
        $before = $dailyCheck->qrpDetail->before;

        DB::beginTransaction();

        try {

            Storage::disk('public')->delete("image/{$before}");
            Notification::where('target_id', $id)->delete();
            QrpApproval::where('qrp_detail_id', $dailyCheck->qrpDetail->id)->delete();
            QrpRecomendation::where('qrp_detail_id', $dailyCheck->qrpDetail->id)->delete();
            QrpDetail::where('daily_check_id', $id)->delete();
            DailyCheck::where('id', $id)->delete();

            DB::commit();
            session()->flash('success', 'Data berhasil dihapus');
            return redirect()->route('qrp.daily-checking');
        } catch (\Throwable $th) {
            DB::rollBack();

            session()->flash('error', $th->getMessage());
            return redirect()->route('qrp.daily-checking');
        }
    }

    public function qrpFormDetailEdit($id)
    {
        $dailyCheck = DailyCheck::find(decrypt($id));
        $agent = new Agent();

        if (Auth::user()->id != $dailyCheck->user_id || $dailyCheck->qrpDetail->qrp_status_id != 1) {
            session()->flash('error', 'Anda tidak berhak');
            return redirect()->route('qrp.qrp-form-detail', $id);
        }

        $factors = Factor::get();
        $categories = Category::get();

        return view('qrp.qrp-form-detail-edit', compact('dailyCheck', 'agent', 'factors', 'categories'));
    }

    public function qrpFormUpdate($id, Request $request)
    {
        if ($request->dataUri != "") {
            $validator = Validator::make($request->all(), [
                'factor' => 'required',
                'area' => 'required',
                'description' => 'required',
                'dataUri' => 'nullable',
                'recomendation' => 'required',
                'category' => 'required',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'factor' => 'required',
                'area' => 'required',
                'description' => 'required',
                'galery' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
                'recomendation' => 'required',
                'category' => 'required',
            ]);
        }

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        DB::beginTransaction();

        try {

            $qrpDetail = QrpDetail::where('daily_check_id', $id)->first();
            $before = $qrpDetail->before;

            if ($request->dataUri != "") {
                $dataUri = $request->dataUri;

                if (preg_match('/^data:image\/(\w+);base64,/', $dataUri, $type)) {
                    $data = substr($dataUri, strpos($dataUri, ',') + 1);
                    $ext = strtolower($type[1]);
                    $data = base64_decode($data);
                }

                $filename = time() . '.' . $ext;
                Storage::disk('public')->put("image/{$filename}", $data);
                Storage::disk('public')->delete("image/{$before}");
            } elseif ($request->galery != "") {
                $file = $request->file('galery');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('image', $filename, 'public');
                Storage::disk('public')->delete("image/{$before}");
            }

            DailyCheck::where('id', $id)->update([
                'area' => $request->area,
                'factor_id' => $request->factor
            ]);

            if (!isset($filename)) {
                $filename = $before;
            }

            $qrpDetail->update([
                'description' => $request->description,
                'category_id' => $request->category,
                'before' => $filename,
            ]);

            QrpRecomendation::where('qrp_detail_id', $qrpDetail->id)->update([
                'recomendation' => $request->recomendation
            ]);

            DB::commit();
            session()->flash('success', 'Data berhasil diupdate');
            return redirect()->route('qrp.qrp-form-detail', encrypt($id));
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', $th->getMessage());
            return redirect()->route('qrp.qrp-form-detail', encrypt($id));
        }
    }

    public function approval($id, Request $request)
    {

        $validator = Validator::make($request->all(), [
            'recomendation' => 'nullable',
            'due_date_rev' => 'nullable|date|after_or_equal:today|required_with:due_date_rev_note',
            'due_date_rev_note' => 'nullable|string|required_with:due_date_rev',
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        DB::beginTransaction();

        try {
            $qrpDetail = QrpDetail::where('daily_check_id', $id)->first();

            QrpRecomendation::where('qrp_detail_id', $qrpDetail->id)->where('status', 1)->update([
                'status' => 2
            ]);

            QrpRecomendation::create([
                'qrp_detail_id' => $qrpDetail->id,
                'user_id' => Auth::user()->id,
                'recomendation' => $request->recomendation,
                'status' => 1
            ]);

            QrpApproval::where('qrp_detail_id', $qrpDetail->id)->where('approval_id', Auth::user()->id)->update([
                'approved_at' => now(),
                'status' => 'approved',
            ]);

            $qrpDetail->update([
                'qrp_status_id' => 2
            ]);

            if (empty($request->due_date_rev)) {
                $qrpDetail->update([
                    'due_date' => $request->due_date_rev,
                    'revision_note' => $request->due_date_rev_note
                ]);
            }

            DB::commit();
            session()->flash('success', 'Laporan berhasil diupdate');
            return redirect()->route('qrp.qrp-form-detail', encrypt($id));
        } catch (\Exception $error) {
            DB::rollBack();
            session()->flash('error', $error->getMessage());
            return redirect()->route('qrp.qrp-form-detail', encrypt($id));
        }
    }

    public function confirm(Request $request, $id)
    {
        $request->validate([
            'due_date_confirm' => 'nullable|date|after_or_equal:today|required_with:due_date_confirm_note',
            'due_date_confirm_note' => 'nullable|string|required_with:due_date_confirm',
        ]);

        DB::beginTransaction();

        try {
            $dailyCheck = DailyCheck::find($id);

            QrpApproval::where('qrp_detail_id', $dailyCheck->qrpDetail->id)->where('approval_id', Auth::user()->id)->update([
                'approved_at' => now(),
                'status' => 'approved',
            ]);

            $qrpDetail = QrpDetail::where('daily_check_id', $id)->first();

            $qrpDetail->update([
                'qrp_status_id' => 2
            ]);

            if (!empty($request->due_date_confirm)) {
                $qrpDetail->update([
                    'due_date' => $request->due_date_confirm,
                    'revision_note' => $request->due_date_confirm_note
                ]);
            }

            Notification::create([
                'user_id' => $dailyCheck->user_id,
                'title' => 'Laporan telah dikonfirmasi',
                'body' => 'Kerjakan sesuai rekomendasi',
                'target' => 'qrp',
                'target_id' => $dailyCheck->id,
            ]);

            DB::commit();

            session()->flash('success', 'Laporan berhasil dikonfirmasi');
            return redirect()->route('qrp.qrp-form-detail', encrypt($id));
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', $th->getMessage());
            return redirect()->route('qrp.qrp-form-detail', encrypt($id));
        }
    }

    public function dhCancel(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'recomendation' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        DB::beginTransaction();

        try {
            QrpDetail::where('daily_check_id', $id)->update([
                'qrp_status_id' => 3,
                'recomendation' => $request->recomendation,
                'closed_at' => now()
            ]);

            DB::commit();
            session()->flash('success', 'Laporan berhasil dicancel');
            return redirect()->route('qrp.qrp-form-detail', encrypt($id));
        } catch (\Exception $error) {
            DB::rollBack();
            session()->flash('error', $error->getMessage());
            return redirect()->route('qrp.qrp-form-detail', encrypt($id));
        }
    }

    public function uploadClose($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dataUri' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        DB::beginTransaction();

        try {
            $dataUri = $request->dataUri;

            if (preg_match('/^data:image\/(\w+);base64,/', $dataUri, $type)) {
                $data = substr($dataUri, strpos($dataUri, ',') + 1);
                $ext = strtolower($type[1]);
                $data = base64_decode($data);
            }

            $filename = time() . '.' . $ext;
            Storage::disk('public')->put("image/{$filename}", $data);

            QrpDetail::where('daily_check_id', $id)->update([
                'after' => $filename,
                'after_uploaded_at' => now(),
                'qrp_status_id' => 4
            ]);

            // $dailyCheck = DailyCheck::find($id);

            // if ($dailyCheck->qrpDetail->adh_id && !$dailyCheck->qrpDetail->dh_id) {
            //     $user = $dailyCheck->qrpDetail->adh_id;
            // } elseif ($dailyCheck->qrpDetail->dh_id && !$dailyCheck->qrpDetail->ph_id) {
            //     $user = $dailyCheck->qrpDetail->dh_id;
            // } elseif ($dailyCheck->qrpDetail->ph_id) {
            //     $user = $dailyCheck->qrpDetail->ph_id;
            // }

            // Notification::create([
            //     'user_id' => $user,
            //     'title' => 'Pekerjaan telah dilakukan',
            //     'body' => 'Pastikan penyelesaian sesuai dengan rekomendasi',
            //     'target' => 'qrp',
            //     'target_id' => $dailyCheck->id,

            // ]);

            DB::commit();
            session()->flash('success', 'Gambar penyelesaian berhasil diupload');
            return redirect()->route('qrp.qrp-form-detail', encrypt($id));
        } catch (\Exception $error) {
            DB::rollBack();
            session()->flash('error', $error->getMessage());
            return redirect()->route('qrp.qrp-form-detail', encrypt($id));
        }
    }

    public function uploadCloseEdit($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dataUri' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        DB::beginTransaction();

        try {
            $dataUri = $request->dataUri;

            if (preg_match('/^data:image\/(\w+);base64,/', $dataUri, $type)) {
                $data = substr($dataUri, strpos($dataUri, ',') + 1);
                $ext = strtolower($type[1]);
                $data = base64_decode($data);
            }

            $filename = time() . '.' . $ext;
            Storage::disk('public')->put("image/{$filename}", $data);

            $qrpDetail = QrpDetail::where('daily_check_id', $id)->first();
            $after = $qrpDetail->after;

            QrpDetail::where('daily_check_id', $id)->update([
                'after' => $filename,
                'after_uploaded_at' => now(),
            ]);


            Storage::disk('public')->delete("image/{$after}");

            DB::commit();
            session()->flash('success', 'Gambar penyelesaian berhasil diupdate');
            return redirect()->route('qrp.qrp-form-detail', encrypt($id));
        } catch (\Exception $error) {
            DB::rollBack();
            session()->flash('error', $error->getMessage());
            return redirect()->route('qrp.qrp-form-detail', encrypt($id));
        }
    }

    public function uploadCloseGalery($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'galery' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        DB::beginTransaction();

        try {
            $file = $request->file('galery');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('image', $filename, 'public');

            QrpDetail::where('daily_check_id', $id)->update([
                'after' => $filename,
                'after_uploaded_at' => now(),
                'qrp_status_id' => 4
            ]);

            $dailyCheck = DailyCheck::find($id);

            $user = $dailyCheck->qrpDetail->qrpApprovals->sortByDesc('id')->first()->approval_id;

            Notification::create([
                'user_id' => $user,
                'title' => 'Pekerjaan telah dilakukan',
                'body' => 'Pastikan penyelesaian sesuai dengan rekomendasi',
                'target' => 'qrp',
                'target_id' => $dailyCheck->id,
            ]);

            DB::commit();
            session()->flash('success', 'Gambar penyelesaian berhasil diupload');
            return redirect()->route('qrp.qrp-form-detail', encrypt($id));
        } catch (\Exception $error) {
            DB::rollBack();
            session()->flash('error', $error->getMessage());
            return redirect()->route('qrp.qrp-form-detail', encrypt($id));
        }
    }

    public function uploadCloseGaleryEdit($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'galery' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        DB::beginTransaction();

        try {
            $file = $request->file('galery');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('image', $filename, 'public');

            $qrpDetail = QrpDetail::where('daily_check_id', $id)->first();
            $after = $qrpDetail->after;

            QrpDetail::where('daily_check_id', $id)->update([
                'after' => $filename,
                'after_uploaded_at' => now(),
            ]);

            Storage::disk('public')->delete("image/{$after}");

            DB::commit();
            session()->flash('success', 'Gambar penyelesaian berhasil diupdate');
            return redirect()->route('qrp.qrp-form-detail', encrypt($id));
        } catch (\Exception $error) {
            DB::rollBack();
            session()->flash('error', $error->getMessage());
            return redirect()->route('qrp.qrp-form-detail', encrypt($id));
        }
    }

    public function close($id)
    {
        DB::beginTransaction();

        try {

            QrpDetail::where('daily_check_id', $id)->update([
                'closed_at' => now(),
                'qrp_status_id' => 5
            ]);

            $dailyCheck = DailyCheck::find($id);

            Notification::create([
                'user_id' => $dailyCheck->user_id,
                'title' => 'Laporan Close',
                'body' => 'Pekerjaan telah selesai',
                'target' => 'qrp',
                'target_id' => $dailyCheck->id
            ]);

            DB::commit();
            session()->flash('success', 'Laporan telah close');
            return redirect()->route('qrp.qrp-form-detail', encrypt($id));
        } catch (\Exception $error) {
            DB::rollBack();
            session()->flash('error', $error->getMessage());
            return redirect()->route('qrp.qrp-form-detail', encrypt($id));
        }
    }

    public function tolakOpen(Request $request, $id)
    {
        $request->validate([
            'recomendation' => 'required|string',
        ]);

        $qrpDetail = QrpDetail::where('daily_check_id', $id)->first();

        QrpRecomendation::where('qrp_detail_id', $id)->update([
            'status' => 2
        ]);

        QrpRecomendation::create([
            'qrp_detail_id' => $qrpDetail->id,
            'user_id' => Auth::user()->id,
            'recomendation' => $request->recomendation,
            'status' => 1
        ]);

        $qrpDetail->update([
            'qrp_status_id' => 2,
            'after_uploaded_at' => null,
            'after' => null
        ]);

        Notification::create([
            'user_id' => $qrpDetail->dailyCheck->user_id,
            'title' => 'Laporan ditolak',
            'body' => 'Mohon ulangi sesuai rekomendasi',
            'target' => 'qrp',
            'target_id' => $id,

        ]);

        $after = $qrpDetail->after;
        Storage::disk('public')->delete("image/{$after}");

        session()->flash('success', 'Laporan ditolak');
        return redirect()->route('qrp.qrp-form-detail', encrypt($id));
    }

    public function riseUp($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'riseup' => 'required',
            // 'due_date_rise' => 'nullable|date|after_or_equal:today|required_with:due_date_rise_note',
            // 'due_date_rise_note' => 'nullable|string|required_with:due_date_rise',
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $dailyCheck = DailyCheck::find($id);

        DB::beginTransaction();

        try {

            QrpApproval::create([
                'qrp_detail_id' => $dailyCheck->qrpDetail->id,
                'approval_id' => $request->riseup,
                'status' => 'waiting'
            ]);

            if (!empty($request->due_date_rise)) {
                QrpDetail::where('daily_check_id', $id)->update([
                    'due_date' => $request->due_date_rise,
                    'revision_note' => $request->due_date_rise_note
                ]);
            }

            Notification::create([
                'user_id' => $request->riseup,
                'title' => 'Approve safety comitee',
                'body' => 'Anda memiliki tugas untuk approve safety comitee',
                'target' => 'qrp',
                'target_id' => $id,
            ]);

            DB::commit();
            session()->flash('success', 'Laporan berhasil dirise up');
            return redirect()->route('qrp.qrp-form-detail', encrypt($id));
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', $th->getMessage());
            return redirect()->route('qrp.qrp-form-detail', encrypt($id));
        }
    }

    public function tindakLanjut($id)
    {
        $dailyCheck = DailyCheck::findOrFail(decrypt($id));
        $agent = new Agent();
        return view('qrp.tindak-lanjut', compact('dailyCheck', 'agent'));
    }

    public function tindakLanjutLive(Request $request, $id)
    {
        $request->validate([
            'dataUri' => 'required'
        ]);

        $dataUri = $request->dataUri;

        if (preg_match('/^data:image\/(\w+);base64,/', $dataUri, $type)) {
            $data = substr($dataUri, strpos($dataUri, ',') + 1);
            $ext = strtolower($type[1]);
            $data = base64_decode($data);
        }

        $filename = time() . '.' . $ext;
        Storage::disk('public')->put("image/{$filename}", $data);

        $qrpDetail = QrpDetail::where('daily_check_id', $id)->first();
        $after = $qrpDetail->after;

        $qrpDetail->update([
            'after' => $filename,
            'after_uploaded_at' => now(),
            'qrp_status_id' => 4
        ]);

        Storage::disk('public')->delete("image/{$after}");

        session()->flash('success', 'Foto tindak lanjut berhasil disimpan');
        return redirect()->route('qrp.qrp-form-detail', encrypt($id));
    }

    public function tindakLanjutGallery(Request $request, $id)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png|max:5120',
        ]);

        $qrpDetail = QrpDetail::where('daily_check_id', $id)->first();
        $after = $qrpDetail->after;

        $file = $request->file('image');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('image', $filename, 'public');

        $qrpDetail->update([
            'after' => $filename,
            'after_uploaded_at' => now(),
            'qrp_status_id' => 4
        ]);

        Storage::disk('public')->delete("image/{$after}");

        session()->flash('success', 'Foto tindak lanjut berhasil disimpan');
        return redirect()->route('qrp.qrp-form-detail', encrypt($id));
    }
}
