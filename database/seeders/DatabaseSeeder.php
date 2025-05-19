<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\QrpStatus;
use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        QrpStatus::create([
            'name' => 'Menunggu Konfirmasi DH',
            'class' => 'badge bg-info text-white text-uppercase'
        ]);

        QrpStatus::create([
            'name' => 'Sedang dikerjakan',
            'class' => 'badge bg-primary text-white text-uppercase'
        ]);
        
        QrpStatus::create([
            'name' => 'Cancel',
            'class' => 'badge bg-warning text-dark text-uppercase'
        ]);

        QrpStatus::create([
            'name' => 'Menunggu Konfirmasi penyelesaian',
            'class' => 'badge bg-info text-white text-uppercase'
        ]);

        QrpStatus::create([
            'name' => 'Close',
            'class' => 'badge bg-success text-white text-uppercase'
        ]);

        QrpStatus::create([
            'name' => 'Tolak open',
            'class' => 'badge bg-waring text-dark text-uppercase'
        ]);

        Role::create([
            'role_name' => 'Super User'
        ]);

        Role::create([
            'role_name' => 'Admin'
        ]);
        
        Role::create([
            'role_name' => 'User'
        ]);
        
        Department::create([
            'department_name' => 'EDP'
        ]);
        
        Department::create([
            'department_name' => 'Engineering BCHI'
        ]);
        
        Department::create([
            'department_name' => 'Produksi A'
        ]);
        
        User::factory(10)->create();
        
    }
}
