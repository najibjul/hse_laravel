<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\DailyCheck;
use App\Models\Factor;
use App\Models\Notification;
use App\Models\QrpDetail;
use App\Models\Rank;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Jenssegers\Agent\Agent;

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

        if ($agent->isMobile()) {
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
                ->latest()
                ->paginate(10);
        } else {
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
                ->latest()
                ->get();
        }

        return view('qrp.daily-checking', compact('dailyChecks', 'search', 'agent'));
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

            dd($error->getMessage());
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
        $adhs = User::where('position_id', 3)->where('department_id', Auth::user()->department_id)->orderBy('name')->get();

        return view('qrp.qrp-form', compact('factor', 'categories', 'ranks', 'adhs'));
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
                'adh' => 'required',

            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'area' => 'required',
                'description' => 'required',
                'galery' => 'required|image|mimes:jpeg,png,jpg|max:5120',
                'recomendation' => 'required',
                'category' => 'required',
                'rank' => 'required',
                'adh' => 'required',
            ]);
        }

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        DB::beginTransaction();

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
                'check_status' => 'NG'
            ]);

            $rank = Rank::find($request->rank);
            $due_date = Carbon::now()->addDays($rank->due_day);

            $recomendation[] = [
                'user' => Auth::user()->name . " (" . Auth::user()->nip . ")",
                'recomendation' => $request->recomendation
            ];

            QrpDetail::create([
                'daily_check_id' => $dailyCheck->id,
                'department_id' => Auth::user()->department_id,
                'description' => $request->description,
                'category_id' => $request->category,
                'before' => $filename,
                'recomendation' => json_encode($recomendation),
                'rank_id' => $request->rank,
                'due_date' =>  $due_date,
                'adh_id' => $request->adh,
                'qrp_status_id' => 1,
            ]);

            Notification::create([
                'user_id' => $request->adh,
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
        $deptHeads = User::where('department_id', $dailyCheck->qrpDetail->department_id)->where('position_id', 2)->get();
        $plantHeads = User::where('position_id', 1)->get();
        return view('qrp.qrp-form-detail', compact('dailyCheck', 'agent', 'adhs', 'deptHeads', 'plantHeads'));
    }

    public function qrpFormDelete($id)
    {
        $dailyCheck = DailyCheck::find($id);
        $before = $dailyCheck->qrpDetail->before;

        DB::beginTransaction();

        try {

            Storage::disk('public')->delete("image/{$before}");
            Notification::where('target_id', $id)->delete();
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
        $adhs = $adhs = User::where('position_id', 3)->where('department_id', Auth::user()->department_id)->orderBy('name')->get();

        if (Auth::user()->id != $dailyCheck->user_id || $dailyCheck->qrpDetail->qrp_status_id != 1) {
            session()->flash('error', 'Anda tidak berhak');
            return redirect()->route('qrp.qrp-form-detail', $id);
        }

        $factors = Factor::get();
        $categories = Category::get();

        return view('qrp.qrp-form-detail-edit', compact('dailyCheck', 'agent', 'factors', 'categories', 'adhs'));
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
                'adh' => 'required',

            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'factor' => 'required',
                'area' => 'required',
                'description' => 'required',
                'galery' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
                'recomendation' => 'required',
                'category' => 'required',
                'adh' => 'required',
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

            $recomendation[] = [
                'user' => Auth::user()->name . " (" . Auth::user()->nip . ")",
                'recomendation' => $request->recomendation
            ];

            if (!isset($filename)) {
                $filename = $before;
            }

            $qrpDetail->update([
                'description' => $request->description,
                'category_id' => $request->category,
                'before' => $filename,
                'recomendation' => $recomendation,
                'adh_id' => $request->adh
            ]);


            DB::commit();
            session()->flash('success', 'Data berhasil diupdate');
            return redirect()->route('qrp.qrp-form-detail', encrypt($id));
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th->getMessage());
            session()->flash('error', $th->getMessage());
            return redirect()->route('qrp.qrp-form-detail', encrypt($id));
        }
    }

    public function approval($id, Request $request)
    {

        $validator = Validator::make($request->all(), [
            'recomendation' => 'nullable'
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        DB::beginTransaction();

        try {
            $qrpDetail = QrpDetail::where('daily_check_id', $id)->first();
            $oldRecomendation = json_decode($qrpDetail->recomendation);

            $recomendation = [
                'user' => Auth::user()->name . " (" . Auth::user()->nip . ")",
                'recomendation' => $request->recomendation
            ];

            $oldRecomendation[] = $recomendation;

            $oldRecomendation = json_encode($oldRecomendation);

            if (Auth::user()->position_id == 3) {

                QrpDetail::where('daily_check_id', $id)->update([
                    'adh_approve_date' => now(),
                    'recomendation' => $oldRecomendation,
                    'qrp_status_id' => 2
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

    public function confirm($id)
    {
        DB::beginTransaction();

        try {
            $dailyCheck = DailyCheck::find($id);

            if (Auth::user()->position_id == 3) {
                QrpDetail::where('daily_check_id', $id)->update([
                    'adh_approve_date' => now(),
                    'qrp_status_id' => 2
                ]);
            } elseif (Auth::user()->position_id == 2) {
                QrpDetail::where('daily_check_id', $id)->update([
                    'dh_approve_date' => now(),
                    'qrp_status_id' => 2
                ]);
            } elseif (Auth::user()->position_id == 1) {
                QrpDetail::where('daily_check_id', $id)->update([
                    'ph_approve_date' => now(),
                    'qrp_status_id' => 2
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

            $dailyCheck = DailyCheck::find($id);

            if ($dailyCheck->qrpDetail->adh_id && !$dailyCheck->qrpDetail->dh_id) {
                $user = $dailyCheck->qrpDetail->adh_id;
            } elseif ($dailyCheck->qrpDetail->dh_id && !$dailyCheck->qrpDetail->ph_id) {
                $user = $dailyCheck->qrpDetail->dh_id;
            } elseif ($dailyCheck->qrpDetail->ph_id) {
                $user = $dailyCheck->qrpDetail->ph_id;
            }

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

            if ($dailyCheck->qrpDetail->adh_id && !$dailyCheck->qrpDetail->dh_id) {
                $user = $dailyCheck->qrpDetail->adh_id;
            } elseif ($dailyCheck->qrpDetail->dh_id && !$dailyCheck->qrpDetail->ph_id) {
                $user = $dailyCheck->qrpDetail->dh_id;
            } elseif ($dailyCheck->qrpDetail->ph_id) {
                $user = $dailyCheck->qrpDetail->ph_id;
            }

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
                'target_id' => $dailyCheck->id,

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
        $validator = Validator::make($request->all(), [
            'recomendation' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        DB::beginTransaction();

        try {
            $qrpDetail = QrpDetail::where('daily_check_id', $id)->first();
            $after = $qrpDetail->after;
            $oldRecomendation = json_decode($qrpDetail->recomendation);

            $recomendation = [
                'user' => Auth::user()->name . " (" . Auth::user()->nip . ")",
                'recomendation' => $request->recomendation
            ];

            $oldRecomendation[] = $recomendation;

            QrpDetail::where('daily_check_id', $id)->update([
                'recomendation' => $oldRecomendation,
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

            Storage::disk('public')->delete("image/{$after}");

            DB::commit();
            session()->flash('success', 'Laporan ditolak');
            return redirect()->route('qrp.qrp-form-detail', encrypt($id));
        } catch (\Exception $error) {
            DB::rollBack();
            session()->flash('error', $error->getMessage());
            return redirect()->route('qrp.qrp-form-detail', encrypt($id));
        }
    }

    public function riseUp($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'riseup' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $dailyCheck = DailyCheck::find($id);

        DB::beginTransaction();

        try {
            if (!$dailyCheck->qrpDetail->dh_id) {
                QrpDetail::where('daily_check_id', $id)->update([
                    'dh_id' => $request->riseup
                ]);
            }

            if (!$dailyCheck->qrpDetail->ph_id && $dailyCheck->qrpDetail->dh_id) {
                QrpDetail::where('daily_check_id', $id)->update([
                    'ph_id' => $request->riseup
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
}
