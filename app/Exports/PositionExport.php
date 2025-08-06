<?php

namespace App\Exports;

use App\Models\Position;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PositionExport implements FromCollection, WithHeadings, WithMapping
{
    protected $iteration = 0;
    protected $param;

    public function __construct($param)
    {
        $this->param = $param;
    }

    public function collection()
    {
        return Position::when($this->param, function($q) {
            $q->where('position_name', 'like', "%$this->param%"); 
        })->get();
    }

    public function map($position): array
    {
        return [
            ++$this->iteration,
            $position->position_name
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
