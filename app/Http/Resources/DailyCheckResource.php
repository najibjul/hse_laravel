<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class DailyCheckResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'activity' => $this->activity,
            'area' => $this->area,
            'check_status' => $this->check_status,
            'created_by' => $this->user->name ?? '-',
            'creator_nip' => $this->user->nip ?? '-',
            'leader_id' => $request->user()->leader_id ?? null,
            'leader_name' => $request->user()->leader?->name ?? '-',
            'leader_nip' => $request->user()->leader?->nip ?? '-',
            'factor_name' => $this->factor->factor_name ?? '-',
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'description' => $this->qrpDetail?->description,
            'due_date' => $this->qrpDetail?->due_date ? Carbon::parse($this->qrpDetail->due_date)->format('Y-m-d') : null,
            'revision_note' => $this->qrpDetail?->revision_note,
            'status' => $this->qrpDetail?->qrpStatus->name ?? '-',
            'before' => $this->qrpDetail?->before ? Storage::url('image/'. $this->qrpDetail->before) : null,
            'after' => $this->qrpDetail?->after ? Storage::url('image/'. $this->qrpDetail->after) : null,
            'qrp_status_id' => $this->qrpDetail?->qrp_status_id,
            'qrpApprovals' => $this->qrpDetail?->qrpApprovals->map(function ($approval) {
                return [
                    'id' => $approval->id,
                    'approval_id' => $approval->approval_id,
                    'approval_name' => $approval->approval->name ?? '-',
                    'status' => $approval->status,
                    'approved_at' => $approval->approved_at ? Carbon::parse($approval->approved_at)->format('Y-m-d H:i:s') : null,
                    'created_at' => $approval->created_at ? $approval->created_at->format('Y-m-d H:i:s') : null,
                ];
            }) ?? [],
            'recomendations' => $this->qrpDetail?->qrpRecomendations->map(function ($recomendation) {
                return [
                    'user_name' => $recomendation->user->name ?? '-',
                    'user_nip' => $recomendation->user->nip ?? '-',
                    'recomendation' => $recomendation->recomendation,
                    'created_at' => $recomendation->created_at ? $recomendation->created_at->format('Y-m-d H:i:s') : null,
                ];
            }) ?? []
        ];
    }
}
