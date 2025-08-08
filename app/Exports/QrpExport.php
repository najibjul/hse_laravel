<?php

namespace App\Exports;

use App\Models\DailyCheck;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class QrpExport implements FromCollection, WithHeadings, WithMapping
{
    protected $param, $start_date, $end_date;
    protected $iteration = 0;

    public function __construct($param, $start_date, $end_date)
    {
        $this->param = $param;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function collection()
    {
        return DailyCheck::leftJoin('users', 'users.id', 'daily_checks.user_id')
        ->leftJoin('factors', 'factors.id', 'daily_checks.factor_id')
        ->leftJoin('qrp_details', 'qrp_details.daily_check_id','daily_checks.id')
        ->leftJoin('qrp_statuses', 'qrp_statuses.id', 'qrp_details.qrp_status_id')
        ->when($this->param, function($q) {
            $q->where(function($q){
                $q->where('users.name','like',"%$this->param%")
                ->orWhere('nip','like',"%$this->param%")
                ->orWhere('activity','like',"%$this->param%")
                ->orWhere('description','like',"%$this->param%")
                ->orWhere('area','like',"%$this->param%")
                ->orWhere('factor_name','like',"%$this->param%")
                ->orWhere('check_status','like',"%$this->param%")
                ->orWhere('qrp_statuses.name','like',"%$this->param%");
            });
        })
        ->when($this->start_date, function($q){
            $q->whereDate('daily_checks.created_at', '>=', $this->start_date)->whereDate('daily_checks.created_at', '<=', $this->end_date);
        })
        ->select('users.name as user_name', 'nip', 'activity',  'area', 'factor_name', 'check_status', 'daily_checks.created_at as create_date', 'description', 'qrp_statuses.name as status')
        ->get();
    }

    public function map($data): array
    {
        return [
            ++$this->iteration,
            $data->user_name,
            $data->nip,
            $data->activity ?? $data->description,
            $data->area,
            $data->create_date,
            $data->factor_name,
            $data->check_status,
            $data->status
        ];
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA',
            'NIP',
            'AKTIVITAS / PROBLEM',
            'AREA',
            'TANGGAL',
            'FAKTOR',
            'STATUS CEK',
            'STATUS TERAKHIR'
        ];
    }
}
