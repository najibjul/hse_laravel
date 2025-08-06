<?php

namespace App\Exports;

use App\Models\Department;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DepartmentExport implements FromCollection, WithHeadings, WithMapping
{
    protected $iteration = 0;
    protected $param;

    public function __construct($param)
    {
        $this->param = $param;
    }

    public function collection()
    {
        return Department::when($this->param, function($q) {
            $q->where('department_name', 'like', "%$this->param%"); 
        })->get();
    }

    public function map($department): array
    {
        return [
            ++$this->iteration,
            $department->department_name
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
