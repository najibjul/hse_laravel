<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DailyCheckResource;
use App\Http\Resources\DetailReportResource;
use App\Models\DailyCheck;
use App\Models\Notification;
use App\Models\QrpApproval;
use App\Models\QrpDetail;
use App\Models\QrpRecomendation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CheckController extends Controller
{
    public function reportSummary(Request $request)
    {
        $openCount = DailyCheck::where('user_id', $request->user()->id)
            ->whereHas('qrpDetail', function ($q) {
                $q->whereIn('qrp_status_id', [1, 2, 4]);
            })
            ->count();

        $now = Carbon::now();

        $closeCurrentMonth = QrpDetail::where('qrp_status_id', 5)
            ->whereNotNull('closed_at')
            ->whereYear('closed_at', $now->year)
            ->whereMonth('closed_at', $now->month)
            ->whereHas('dailyCheck', function ($q) use ($request) {
                $q->where('user_id', $request->user()->id);
            })
            ->count();

        return response()->json([
            'open_count' => $openCount,
            'close_per_month' => [
                'month' => (int) $now->month,
                'month_name' => $now->format('F'),
                'year' => (int) $now->year,
                'total' => $closeCurrentMonth,
            ],
        ]);
    }

    public function reports(Request $request)
    {
        $limit = $request->limit;
        $offset = ($request->page - 1) * $limit;

        $reports = DailyCheck::select('id', 'user_id', 'activity', 'area', 'factor_id', 'check_status', 'created_at')
            ->where('user_id', $request->user()->id)
            ->with(['factor' => fn($q) => $q->select('id', 'factor_name')])
            ->with(['user' => fn($q) => $q->select('id', 'name', 'nip')])
            ->with([
                'qrpDetail' => function ($q) {
                    $q->select('id', 'daily_check_id', 'description', 'category_id', 'due_date', 'revision_note', 'qrp_status_id', 'before', 'after')
                        ->with(['category' => fn($q) => $q->select('id', 'category_name')])
                        ->with(['qrpStatus' => fn($q) => $q->select('id', 'name')])
                        ->with([
                            'qrpApprovals' => fn($q) => $q
                                ->select('id', 'qrp_detail_id', 'approval_id', 'status', 'approved_at', 'created_at')
                                ->orderBy('created_at')
                                ->with(['approval' => fn($q) => $q->select('id', 'name', 'nip')]),
                        ])
                        ->with([
                            'qrpRecomendations' => fn($q) => $q
                                ->select('qrp_detail_id', 'user_id', 'recomendation', 'id', 'created_at')
                                ->orderBy('created_at')
                                ->with(['user' => fn($q) => $q->select('id', 'name', 'nip')]),
                        ]);
                },
            ])
            ->orderByDesc('id')
            ->paginate(10);

        return DailyCheckResource::collection($reports);
    }

    public function confirmationReports(Request $request)
    {
        $reports = DailyCheck::select('id', 'user_id', 'activity', 'area', 'factor_id', 'check_status', 'created_at')
            ->whereHas('qrpDetail.qrpApprovals', function ($q) use ($request) {
                $q->where('approval_id', $request->user()->id)->where('status', 'waiting');
            })
            ->with(['factor' => fn($q) => $q->select('id', 'factor_name')])
            ->with(['user' => fn($q) => $q->select('id', 'name', 'nip')])
            ->with([
                'qrpDetail' => function ($q) use ($request) {
                    $q->select('id', 'daily_check_id', 'description', 'category_id', 'due_date', 'qrp_status_id', 'before', 'after')
                        ->with(['category' => fn($q) => $q->select('id', 'category_name')])
                        ->with(['qrpStatus' => fn($q) => $q->select('id', 'name')])
                        ->with([
                            'qrpApprovals' => fn($q) => $q
                                ->select('id', 'qrp_detail_id', 'approval_id', 'status', 'approved_at', 'created_at')
                                ->where('approval_id', $request->user()->id)
                                ->where('status', 'waiting')
                                ->orderBy('created_at')
                                ->with(['approval' => fn($q) => $q->select('id', 'name', 'nip')])
                        ])
                        ->with([
                            'qrpRecomendations' => fn($q) => $q
                                ->select('qrp_detail_id', 'user_id', 'recomendation', 'id', 'created_at')
                                ->orderBy('created_at')
                                ->with(['user' => fn($q) => $q->select('id', 'name', 'nip')]),
                        ]);
                },
            ])
            ->orderByDesc('id')
            ->paginate(10);


        return DailyCheckResource::collection($reports);
    }

    public function showReport($id)
    {
        $data = DailyCheck::where('id', $id)
            ->select('id', 'activity', 'area', 'factor_id', 'check_status', 'created_at', 'qrp_status_id')
            ->with([
                'qrpDetail' => function ($q) {
                    $q->select('id', 'daily_check_id', 'description', 'category_id', 'due_date', 'qrp_status_id', 'before', 'rank_id')
                        ->with(['category' => fn($q) => $q->select('id', 'category_name')])
                        ->with(['qrpStatus' => fn($q) => $q->select('id', 'name')])
                        ->with(['rank' => fn($q) => $q->select('id', 'rank_name')]);
                },
            ])
            ->first();

        //return DetailReportResource::collection($data);
        return new DetailReportResource($data);
    }

    public function deleteReport($id)
    {
        $report = DailyCheck::with('qrpDetail')->findOrFail($id);

        if ($report->qrpDetail) {
            if ($report->qrpDetail->before && Storage::disk('public')->exists("image/{$report->qrpDetail->before}")) {
                Storage::disk('public')->delete("image/{$report->qrpDetail->before}");
            }
        }

        QrpApproval::where('qrp_detail_id', $report->qrpDetail->id)->delete();

        QrpRecomendation::where('qrp_detail_id', $report->qrpDetail->id)->delete();

        QrpDetail::where('daily_check_id', $id)->delete();

        $report->delete();

        return response()->json(['message' => 'Report deleted successfully']);
    }

    public function approve(Request $request, $id)
    {
        $dailyCheck = DailyCheck::with('qrpDetail')->findOrFail($id);

        $validated = $request->validate([
            'due_date' => 'required|date',
        ]);

        $currentDueDate = $dailyCheck->qrpDetail?->due_date ? Carbon::parse($dailyCheck->qrpDetail->due_date)->toDateString() : null;

        $newDueDate = Carbon::parse($validated['due_date'])->toDateString();

        if ($newDueDate !== $currentDueDate) {
            $request->validate([
                'due_date_note' => 'required|string',
            ]);
        }

        $dailyCheck->qrpDetail->update([
            'qrp_status_id' => 2,
        ]);

        if (!empty($request->due_date_note)) {
            $dailyCheck->qrpDetail->update([
                'due_date' => $request->due_date,
                'revision_note' => $request->due_date_note,
            ]);
        }

        QrpApproval::where('qrp_detail_id', $dailyCheck->qrpDetail->id)
            ->where('approval_id', $request->user()->id)
            ->update([
                'status' => 'approved',
                'approved_at' => Carbon::now(),
            ]);

        Notification::create([
            'user_id' => $dailyCheck->user_id,
            'title' => 'Laporan telah dikonfirmasi',
            'body' => 'Kerjakan sesuai rekomendasi',
            'target' => 'qrp',
            'target_id' => $dailyCheck->id,
        ]);

        return response()->json(['message' => 'Report approved successfully']);
    }

    public function tambahRekomendasi(Request $request, $id)
    {
        $request->validate([
            'recommendation' => 'required|string',
            'due_date' => 'required',
        ]);

        $dailyCheck = DailyCheck::with('qrpDetail')->findOrFail($id);

        if (!$dailyCheck->qrpDetail) {
            return response()->json(['message' => 'QRP detail not found'], 404);
        }

        if (Carbon::parse($dailyCheck->qrpDetail->due_date)->toDateString() !== Carbon::parse($request->due_date)->toDateString()) {
            $request->validate([
                'due_date_note' => 'required|string',
            ]);

            $dailyCheck->qrpDetail->update([
                'due_date' => Carbon::parse($request->due_date)->toDateString(),
                'revision_note' => $request->due_date_note,
            ]);
        }

        QrpRecomendation::where('qrp_detail_id', $dailyCheck->qrpDetail->id)->update(['status' => 2]);

        QrpRecomendation::create([
            'qrp_detail_id' => $dailyCheck->qrpDetail->id,
            'user_id' => $request->user()->id,
            'recomendation' => $request->recommendation,
            'status' => 1,
        ]);

        $dailyCheck->qrpDetail->update([
            'qrp_status_id' => 2,
        ]);

        QrpApproval::where('qrp_detail_id', $dailyCheck->qrpDetail->id)
            ->where('approval_id', $request->user()->id)
            ->update([
                'status' => 'approved',
                'approved_at' => Carbon::now(),
            ]);

        Notification::create([
            'user_id' => $dailyCheck->user_id,
            'title' => 'Laporan telah dikonfirmasi',
            'body' => 'Kerjakan sesuai rekomendasi',
            'target' => 'qrp',
            'target_id' => $dailyCheck->id,
        ]);

        return response()->json(
            [
                'message' => 'Recomendation added successfully',
            ],
            201,
        );
    }

    public function riseUp(Request $request, $id)
    {
        $request->validate([
            'riseup' => 'required|exists:users,id',
        ]);

        $dailyCheck = DailyCheck::with('qrpDetail')->findOrFail($id);

        if (!$dailyCheck->qrpDetail) {
            return response()->json(['message' => 'QRP detail not found'], 404);
        }

        QrpApproval::where('qrp_detail_id', $dailyCheck->qrpDetail->id)->update([
            'status' => 'riseup',
            'approved_at' => Carbon::now(),
        ]);

        QrpApproval::create([
            'qrp_detail_id' => $dailyCheck->qrpDetail->id,
            'approval_id' => $request->riseup,
            'status' => 'waiting',
        ]);

        Notification::create([
            'user_id' => $request->riseup,
            'title' => 'Approve safety comitee',
            'body' => 'Anda memiliki tugas untuk approve safety comitee',
            'target' => 'qrp',
            'target_id' => $dailyCheck->id,
        ]);

        return response()->json([
            'message' => 'Report risked up successfully',
        ], 201);
    }

    public function uploadBuktiPenyelesaian(Request $request, $daily_check_id)
    {
        $request->validate([
            'after' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $dailyCheck = DailyCheck::with(['qrpDetail', 'qrpDetail.qrpApprovals'])->findOrFail($daily_check_id);

        if (!$dailyCheck->qrpDetail) {
            return response()->json(['message' => 'QRP detail not found'], 404);
        }

        $file = $request->file('after');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('image', $filename, 'public');

        $oldAfter = $dailyCheck->qrpDetail->after;

        $dailyCheck->qrpDetail->update([
            'after' => $filename,
            'after_uploaded_at' => Carbon::now(),
            'qrp_status_id' => 4,
        ]);

        if ($oldAfter && Storage::disk('public')->exists("image/{$oldAfter}")) {
            Storage::disk('public')->delete("image/{$oldAfter}");
        }

        $lastApproval = $dailyCheck->qrpDetail->qrpApprovals->sortByDesc('id')->first();

        if (!$oldAfter && $lastApproval) {
            Notification::create([
                'user_id' => $lastApproval->approval_id,
                'title' => 'Pekerjaan telah dilakukan',
                'body' => 'Pastikan penyelesaian sesuai dengan rekomendasi',
                'target' => 'qrp',
                'target_id' => $dailyCheck->id,
            ]);
        }

        return response()->json([
            'message' => $oldAfter ? 'Bukti penyelesaian berhasil diupdate' : 'Bukti penyelesaian berhasil diupload',
            'after' => Storage::url("image/{$filename}"),
        ]);
    }
}
