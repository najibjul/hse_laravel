<?php

namespace App\Exports;

use App\Models\CostCenter;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CostCenterExport implements FromCollection, WithHeadings, WithMapping
{
    protected $iteration = 0;
    protected $param;

    public function __construct($param)
    {
        $this->param = $param;
    }

    public function collection()
    {
        return CostCenter::when($this->param, function($q) {
            $q->where('cost_center_name', 'like', "%$this->param%"); 
        })->get();
    }

    public function map($costCenter): array
    {
        return [
            ++$this->iteration,
            substr($costCenter->cost_center_name,0,3)
        ];
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA',
        ];
    }
}
