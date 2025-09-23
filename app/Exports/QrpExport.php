<?php

namespace App\Exports;

use App\Models\DailyCheck;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class QrpExport implements FromCollection, WithHeadings, WithMapping
{
    protected $cari_user, $cari_aktifitas, $cari_area, $start_date, $end_date, $cari_faktor, $cari_cek, $cari_status;
    protected $iteration = 0;

    public function __construct($cari_user, $cari_aktifitas, $cari_area, $start_date, $end_date, $cari_faktor, $cari_cek, $cari_status)
    {

        $this->cari_user = $cari_user;
        $this->cari_aktifitas = $cari_aktifitas;
        $this->cari_area = $cari_area;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->cari_faktor = $cari_faktor;
        $this->cari_cek = $cari_cek;
        $this->cari_status = $cari_status;
    }

    public function collection()
    {
        $cari_user = $this->cari_user;
        $cari_aktifitas = $this->cari_aktifitas;
        $cari_area = $this->cari_area;
        $start_date = $this->start_date;
        $end_date = $this->end_date;
        $cari_faktor = $this->cari_faktor;
        $cari_cek = $this->cari_cek;
        $cari_status = $this->cari_status;

        $datas = DailyCheck::leftJoin('users', 'users.id', 'daily_checks.user_id')
            ->leftJoin('factors', 'factors.id', 'daily_checks.factor_id')
            ->leftJoin('qrp_details', 'qrp_details.daily_check_id', 'daily_checks.id')
            ->leftJoin('qrp_statuses', 'qrp_statuses.id', 'qrp_details.qrp_status_id')

            ->when($this->start_date, function ($q) {
                $q->whereDate('daily_checks.created_at', '>=', $this->start_date)->whereDate('daily_checks.created_at', '<=', $this->end_date);
            })
            ->when(Auth::user()->role_id == 3, function ($q) {
                $user_id = Auth::user()->id;
                $user_ids[] = $user_id;
                $team_ids = [$user_id];

                for ($i = 0; $i < User::count(); $i++) {
                    $teams = User::whereIn('leader_id', $user_ids)->select('id')->get();
                    if (count($teams) == 0) {
                        break;
                    } else {
                        $user_ids = $teams->pluck('id')->toArray();
                        $team_ids = array_merge($team_ids, $user_ids);
                    }
                }

                $q->whereIn('user_id', $team_ids);
            })

            ->when($cari_user, function ($q) use ($cari_user) {
                $q->whereHas('user', function ($q) use ($cari_user) {
                    $q->where('name', 'like', "%$cari_user%")
                        ->orWhere('nip', 'like', "%$cari_user%");
                });
            })

            ->when($cari_aktifitas, function ($q) use ($cari_aktifitas) {
                $q->where('activity', 'like', "%$cari_aktifitas%")
                    ->orWhereHas('qrpDetail', function ($q) use ($cari_aktifitas) {
                        $q->where('description', 'like', "%$cari_aktifitas%");
                    });
            })

            ->when($cari_area, function ($q) use ($cari_area) {
                $q->where('area', 'like', "%$cari_area%");
            })

            ->when($start_date && $end_date, function ($q) use ($start_date, $end_date) {
                $q->whereBetween('created_at', [$end_date, $start_date]);
            })

            ->when($cari_faktor, function ($q) use ($cari_faktor) {
                $q->where('factor_id', $cari_faktor);
            })

            ->when($cari_faktor, function ($q) use ($cari_faktor) {
                $q->where('factor_id', $cari_faktor);
            })

            ->when($cari_cek, function ($q) use ($cari_cek) {
                $q->where('check_status', $cari_cek);
            })

            ->when($cari_status, function ($q) use ($cari_status) {
                $q->wherehas('qrpDetail', function ($q) use ($cari_status) {
                    $q->where('qrp_status_id', $cari_status);
                });
            })
            ->select('users.name as user_name', 'nip', 'activity',  'area', 'factor_name', 'check_status', 'daily_checks.created_at as create_date', 'description', 'qrp_statuses.name as status')
            ->get();

            return $datas;
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
