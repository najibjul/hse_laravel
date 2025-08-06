<?php

namespace App\Exports;

use App\Models\Plant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PlantExport implements FromCollection, WithHeadings, WithMapping
{
    protected $iteration = 0;
    protected $param;

    public function __construct($param)
    {
        $this->param = $param;
    }

    public function collection()
    {
        return Plant::when($this->param, function($q) {
            $q->where('plant_name', 'like', "%$this->param%"); 
        })->get();
    }

    public function map($plant): array
    {
        return [
            ++$this->iteration,
            $plant->plant_name
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
