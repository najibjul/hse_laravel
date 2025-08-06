<?php
namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $param;
    protected $iteration = 0;

    public function __construct($param)
    {
        $this->param = $param;
    }

    public function collection()
    {
        return User::leftJoin('departments','departments.id','users.department_id')
                    ->leftJoin('cost_centers','cost_centers.id','users.cost_center_id')
                    ->leftJoin('roles','roles.id','users.role_id')
                    ->leftJoin('positions','positions.id','users.position_id')
                    ->leftJoin('plants','plants.id','users.plant_id')
                    ->leftJoin('users as leader','leader.id','users.leader_id')
                    ->where('users.name', 'like', "%$this->param%")
                    ->orWhere('users.nip', 'like', "%$this->param%")
                    ->orWhere('users.email', 'like', "%$this->param%")
                    ->orWhere('department_name', 'like', "%$this->param%")
                    ->orWhere('cost_center_name', 'like', "%$this->param%")
                    ->orWhere('role_name', 'like', "%$this->param%")
                    ->orWhere('position_name', 'like', "%$this->param%")
                    ->orWhere('plant_name', 'like', "%$this->param%")
                    ->orWhere('leader.name', 'like', "%$this->param%")
                    ->orWhere('leader.nip', 'like', "%$this->param%")
                    ->select('users.name as user_name', 'users.nip as user_nip', 'users.email as user_email', 'department_name', 'cost_center_name', 'role_name', 'position_name', 'plant_name', 'leader.name as leader_name', 'leader.nip as leader_nip')
                    ->get();   
    }

    public function map($user): array
    {
        return [
            ++$this->iteration,
            $user->user_name,
            $user->user_nip,
            $user->user_email,
            $user->department_name,
            substr($user->cost_center_name, 0, 3),
            $user->role_name,
            $user->position_name,
            $user->plant_name,
            $user->leader_name,
            $user->leader_nip,
        ];
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA',
            'NIP',
            'EMAIL',
            'DEPARTMENT',
            'COST CENTER',
            'ROLE',
            'POSITION',
            'PLANT',
            'LEADER NAME',
            'LEADER NIP'
        ];
    }

}
