<?php

namespace App\Exports;

use App\Models\DailyCheck;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ShowExport implements FromView, WithDrawings, WithColumnWidths
{
    protected $dailyCheck_id;

    public function __construct($id)
    {
        $this->dailyCheck_id = $id;
    }

    public function view(): View
    {
        $dailyCheck = DailyCheck::find($this->dailyCheck_id);
        return view('export.qrp', compact('dailyCheck'));
    }

    public function drawings()
    {
        $dailyCheck = DailyCheck::find($this->dailyCheck_id);

        $drawing = new Drawing();
        $drawing->setName('Before');
        $drawing->setDescription('Before');
        $drawing->setPath(storage_path('app/public/image/'. $dailyCheck->qrpDetail->before));
        $drawing->setHeight(90);
        $drawing->setCoordinates('F3');
        
        if ($dailyCheck->qrpDetail->after) {

            $drawing2 = new Drawing();
            $drawing2->setName('After');
            $drawing2->setDescription('After');
            $drawing2->setPath(storage_path('app/public/image/'. $dailyCheck->qrpDetail->after));
            $drawing2->setHeight(90);
            $drawing2->setCoordinates('G3');

            return[$drawing, $drawing2];

        } else {
            return $drawing;
        }
    }

    public function columnWidths(): array
    {
        return [
            'F' => 30,
            'G' => 30,            
        ];
    }
}
