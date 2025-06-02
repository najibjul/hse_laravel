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

        $dailyChecks = DailyCheck::when(Auth::user()->role_id == 3, function ($q) {
            $q->where(function ($q) {

                $q->where('user_id', Auth::user()->id)
                    ->when(Auth::user()->deptHead, function ($q) {
                        return $q->orWhereHas('qrpDetail', function ($q) {
                            return $q->where('dept_head_id', Auth::user()->id);
                        });
                    });
                });
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->whereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%')
                        ->orwhere('nip', 'like', '%' . $search . '%');
                    })
                    ->orWhere('activity', 'like', '%'. $search . '%')
                    ->orWhere('area', 'like', '%'. $search . '%')
                    ->orWhere('check_status', 'like', '%'. $search . '%')
                    ->orWhere('checking_category', 'like', '%'. $search . '%');
                });

            })
            ->latest()
            ->paginate(10);

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
        if (!session('factor')) {
            return redirect()->route('qrp.daily-checking');
        }

        $factor = Factor::find(session('factor'));
        $categories = Category::get();
        $ranks = Rank::get();

        return view('qrp.qrp-form', compact('factor', 'categories', 'ranks'));
    }

    public function searchAdh(Request $request)
    {
        if ($request->has('q')) {
            $search = $request->q;
        } else {
            $search = null;
        }

        $adhs = User::where('department_id', Auth::user()->department_id)
        ->where('position_id', 3)
        ->when($search, function($q) use($search) {
            $q->where(function($q) use($search) {
                $q->where('name', 'like', '%'. $search . '%')
                ->orWhere('nip', 'like', '%'. $search . '%');
            });
        })
        ->select('id', 'name', 'nip')
        ->get();

        return response()->json($adhs);
    }

    public function qrpFormPost(Request $request)
    {
        if (!session('factor')) {
            return redirect()->route('qrp.daily-checking');
        }

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
                    $ext = strtolower($type[1]); // jpg, png, gif, etc.
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

            QrpDetail::create([
                'daily_check_id' => $dailyCheck->id,
                'description' => $request->description,
                'category_id' => $request->category,
                'before' => $filename,
                'recomendation' => $request->recomendation,
                'rank_id' => $request->rank,
                'due_date' =>  $due_date,
                'adh_id' => $request->adh,
                'qrp_status_id' => 1,
            ]);

            Notification::create([
                'user_id' => $request->adh,
                'title' => 'Approve QRP',
                'body' => 'Anda memiliki tugas untuk approve QRP',
                'target' => 'qrp',
                'target_id' => $dailyCheck->id,
                'is_read' => false
            ]);

            DB::commit();

            session()->flash('success', 'Data QRP berhasil dibuat');
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
        return view('qrp.qrp-form-detail', compact('dailyCheck', 'agent', 'adhs'));
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

        if (Auth::user()->id != $dailyCheck->user_id || $dailyCheck->qrpDetail->qrp_status_id != 1) {
            session()->flash('error', 'Anda tidak berhak');
            return redirect()->route('qrp.qrp-form-detail', $id);
        }

        return view('qrp.qrp-form-detail-edit', compact('dailyCheck', 'agent'));
    }

    public function approval($id, Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'recomendation' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        DB::beginTransaction();

        //buat rekomendasi
        
        try {
            $dailyCheck = DailyCheck::find($id);
            $recomendation = $dailyCheck->user->name. " (" . $dailyCheck->user->nip . ")\n<i>" .$dailyCheck->qrpDetail->recomendation. "</i>\n\n";
            $recomendation .= $dailyCheck->qrpDetail->adh->name. " (" . $dailyCheck->qrpDetail->adh->nip . ")\n<i>" . $request->recomendation . "</i>\n\n";

            if (Auth::user()->position_id == 3) {

                QrpDetail::where('daily_check_id', $id)->update([
                    'adh_approve_date' => now(),
                    'recomendation' => $recomendation,
                    'qrp_status_id' => 2
                ]);
            }

            DB::commit();
            session()->flash('success', 'Data QRP berhasil diupdate');
            return redirect()->route('qrp.qrp-form-detail', encrypt($id));

        } catch (\Exception $error) {
            DB::rollBack();
            session()->flash('error', $error->getMessage());
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
            session()->flash('success', 'Data QRP berhasil dicancel');
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
                $ext = strtolower($type[1]); // jpg, png, gif, etc.
                $data = base64_decode($data);
            }

            $filename = time() . '.' . $ext;
            Storage::disk('public')->put("image/{$filename}", $data);

            QrpDetail::where('daily_check_id', $id)->update([
                'after' => $filename,
                'after_uploaded_at' => now(),
                'qrp_status_id' => 4
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

            DB::commit();
            session()->flash('success', 'Gambar penyelesaian berhasil diupload');
            return redirect()->route('qrp.qrp-form-detail', encrypt($id));

        } catch (\Exception $error) {
            DB::rollBack();
            session()->flash('error', $error->getMessage());
            return redirect()->route('qrp.qrp-form-detail', encrypt($id));
        }
    }

    public function close($id, Request $request)
    {
        DB::beginTransaction();

        try {

            QrpDetail::where('daily_check_id', $id)->update([
                'closed_at' => now(),
                'qrp_status_id' => 5
            ]);

            DB::commit();
            session()->flash('success', 'QRP berhasil diclose');
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
            QrpDetail::where('daily_check_id', $id)->update([
                'recomendation' => $request->recomendation,
                'closed_at' => now(),
                'qrp_status_id' => 6
            ]);

            DB::commit();
            session()->flash('success', 'QRP Tolak open');
            return redirect()->route('qrp.qrp-form-detail', encrypt($id));
        } catch (\Exception $error) {
            DB::rollBack();
            session()->flash('error', $error->getMessage());
            return redirect()->route('qrp.qrp-form-detail', encrypt($id));
        }
    }
}
