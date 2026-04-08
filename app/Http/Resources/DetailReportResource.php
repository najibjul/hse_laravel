<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class DetailReportResource extends JsonResource
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
            'factor_name' => $this->factor->factor_name ?? '-',
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'description' => $this->qrpDetail?->description,
            'due_date' => $this->qrpDetail?->due_date ? Carbon::parse($this->qrpDetail->due_date)->format('Y-m-d') : null,
            'status' => $this->qrpDetail?->qrpStatus->name ?? '-',
            'before' => $this->qrpDetail?->before ? Storage::url('image/'. $this->qrpDetail->before) : null,
            'after' => $this->qrpDetail?->after ? Storage::url('image/'. $this->qrpDetail->after) : null,
            'rank' =>  $this->qrpDetail?->rank?->rank_name,
        ];
    }
}
