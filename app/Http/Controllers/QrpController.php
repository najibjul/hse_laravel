<?php

namespace App\Http\Controllers;

use App\Models\DailyCheck;
use App\Models\Depthead;
use App\Models\QrpDetail;
use DB;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Storage;
use Validator;

class QrpController extends Controller
{
    public function dailyChecking(Request $request)
    {
        if ($request->search) {
            $search = $request->search;
        } else {
            $search = null;
        }

        session()->put('checkingCategory', 'man');

        $dailyChecks = DailyCheck::when(auth()->user()->role_id == 3, function ($q) {
            $q->where('user_id', auth()->user()->id)
                ->when(auth()->user()->deptHead, function ($q) {
                    $q->orWhereHas('qrpDetail', function ($q) {
                        $q->where('dept_head_id', auth()->user()->id);
                    });
                });
            })
            ->when($search, function($q) use($search){
                $q->whereHas('user', function($q) use($search){
                    $q->where('name', 'like', '%'. $search .'%')->orWhere('nip', 'like', '%'. $search .'%');
                })
                ->orWhere('activity', 'like', '%'. $search . '%')
                ->orWhere('area', 'like', '%'. $search . '%')
                ->orWhere('check_status', 'like', '%'. $search . '%')
                ->orWhere('checking_category', 'like', '%'. $search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('qrp.daily-checking', compact('dailyChecks', 'search'));
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
                'user_id' => auth()->user()->id,
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

    public function changeCategory()
    {
        $category = session('checkingCategory');

        if ($category == 'man') {
            session()->put('checkingCategory', 'machine');
        } elseif ($category == 'machine') {
            session()->put('checkingCategory', 'material');
        } elseif ($category == 'material') {
            session()->put('checkingCategory', 'method');
        } elseif ($category == 'method') {
            session()->put('checkingCategory', 'environment');
        }
    }

    public function qrpForm()
    {
        if (!session('checkingCategory')) {
            return redirect()->route('qrp.daily-checking');
        }

        return view('qrp.qrp-form');
    }

    public function qrpFormPost(Request $request)
    {
        if (!session('checkingCategory')) {
            return redirect()->route('qrp.daily-checking');
        }

        if ($request->dataUri != "") {
            $validator = Validator::make($request->all(), [
                'area' => 'required',
                'description' => 'required',
                'dataUri' => 'required',
                'recomendation' => 'required'
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'area' => 'required',
                'description' => 'required',
                'galery' => 'required|image|mimes:jpeg,png,jpg|max:5120',
                'recomendation' => 'required'
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

            $deptHead = Depthead::where('department_id', auth()->user()->department_id)->first();

            if (!$deptHead) {
                DB::rollBack();
                session()->flash('error', 'Belum ada Dept. Head');
                return redirect()->route('qrp.qrp-form');
            }

            $dailyCheck = DailyCheck::create([
                'user_id' => auth()->user()->id,
                'area' => $request->area,
                'checking_category' => session('checkingCategory'),
                'check_status' => 'NG'
            ]);


            $a = QrpDetail::create([
                'daily_check_id' => $dailyCheck->id,
                'description' => $request->description,
                'before' => $filename,
                'recomendation' => $request->recomendation,
                'qrp_status_id' => 1,
                'dept_head_id' => $deptHead->user_id
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
        $dailyCheck = DailyCheck::find(decrypt($id));
        return view('qrp.qrp-form-detail', compact('dailyCheck'));
    }

    public function dhApproval($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'recomendation' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        DB::beginTransaction();

        try {
            QrpDetail::where('daily_check_id', $id)->update([
                'dept_head_approved_at' => now(),
                'recomendation' => $request->recomendation,
                'qrp_status_id' => 2
            ]);

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
