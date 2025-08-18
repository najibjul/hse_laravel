<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DeptConfigExport implements FromCollection, WithHeadings, WithMapping
{
    protected $iteration = 0;
    protected $param;

    public function __construct($param)
    {
        $this->param = $param;
    }

    public function collection()
    {
        return User::when($this->param, function ($q) {
            $q->whereHas('adminDepts.department', function ($q) {
                $q->where('department_name', 'like', "%$this->param%");
            })
            ->orWhere(function($q){
                $q->where('name', 'like', "%$this->param%")->orWhere('nip', 'like', "%$this->param%");
            });
        })->where('role_id', '!=', 3)->get();
    }

    public function map($user): array
    {
        return [
            ++$this->iteration,
            $user->name,
            $user->nip,
            count($user->adminDepts) > 0 ? $user->adminDepts->pluck('department.department_name')->implode(', ') : '' 
        ];
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA',
            'NIP',
            'DEPARTEMEN'
        ];
    }
}
