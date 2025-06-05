<?php

namespace App\Exports;

use App\Models\DailyCheck;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class SafetyComiteeExport implements FromView, WithDrawings, WithColumnWidths
{
    public $datas;

    public function view(): View
    {
        $this->datas = DailyCheck::get();
        $datas = $this->datas;
        return view('export.safety-comitee', compact('datas'));
    }

    public function drawings()
    {
        $row = 2;
        foreach ($this->datas as $data) {
            if (isset($data->qrpDetail)) {
                $before = new Drawing();
                $before->setPath(public_path('storage/image/'.$data->qrpDetail->before));
                $before->setWidth(200);
                $before->setHeight(150);
                $before->setCoordinates('K' . $row);
                $image[] = $before;
                if (isset($data->qrpDetail->after)) {
                    $after = new Drawing();
                    $after->setPath(public_path('storage/image/'.$data->qrpDetail->after));
                    $after->setWidth(200);
                    $before->setHeight(150);
                    $after->setCoordinates('L' . $row);   
                    $image[] = $after;
                }
            }
            $row++;
        }
        return $image;
    }

    public function columnWidths(): array
    {
        return [
            'K' => 45,
            'L' => 45
        ];
    }
}
