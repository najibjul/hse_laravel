<?php

namespace App\Exports;

use App\Models\Position;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PositionExport implements FromCollection, WithHeadings, WithMapping
{
    protected $iteration = 0;
    protected $param, $isQrpEnabled;

    public function __construct($param, $isQrpEnabled)
    {
        $this->param = $param;
        $this->isQrpEnabled = $isQrpEnabled;
    }

    public function collection()
    {

        return Position::when($this->param, function($q) {
            $q->where('position_name', 'like', "%$this->param%"); 
        })
        ->when($this->isQrpEnabled, function($q) {
            $q->where('is_qrp_enabled', $this->isQrpEnabled); 
        })
        ->get();
    }

    public function map($position): array
    {
        return [
            ++$this->iteration,
            $position->position_name,
            $position->is_qrp_enabled ? 'Ya' : 'Tidak' 
        ];
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA',
            'QRP_ENABLED'
        ];
    }
}
