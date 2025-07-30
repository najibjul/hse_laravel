<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Department;
use App\Models\Factor;
use App\Models\Position;
use App\Models\QrpStatus;
use App\Models\Rank;
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
            'role_name' => 'Admin Department'
        ]);
        
        Role::create([
            'role_name' => 'User'
        ]);

        Role::create([
            'role_name' => 'Admin HSE'
        ]);
        
        // Department::create([
        //     'department_name' => 'EDP'
        // ]);
        
        // Department::create([
        //     'department_name' => 'Engineering BCHI'
        // ]);
        
        // Department::create([
        //     'department_name' => 'Produksi A'
        // ]);

        // Department::create([
        //     'department_name' => 'HSE'
        // ]);

        // Position::create([
        //     'position_name' => 'Plant Head / HoD'
        // ]);
        
        // Position::create([
        //     'position_name' => 'Dept. Head'
        // ]);

        // Position::create([
        //     'position_name' => 'Asst. Dept. Head'
        // ]);

        // Position::create([
        //     'position_name' => 'Sect. Head'
        // ]);

        // Position::create([
        //     'position_name' => 'Staff'
        // ]);

        // Position::create([
        //     'position_name' => 'Asst. Plant Head'
        // ]);
        
        // User::factory(10)->create();

        Category::create([
            'category_name' => 'Health'
        ]);

        Category::create([
            'category_name' => 'Safety'
        ]);

        Category::create([
            'category_name' => 'Environment'
        ]);

        Rank::create([
            'rank_name' => 'A',
            'due_day' => 1,
            'rank_description' => "Potensi bahaya yang dapat menyebabkan kecelakaan berat, cacat/ hilang anggota tubuh & kematian / hilang hari kerja > 21 hari dan harus dilakukan perbaikan maksimal 1X24 Jam. Reduce hingga pengendalian dilaksanakan sampai Rank C"
        ]);

        Rank::create([
            'rank_name' => 'B',
            'due_day' => 3,
            'rank_description' => "Potensi bahaya yang dapat menyebabkan kecelakaan sedang (lost time injury) & perawatan tim medis atau RWC / line stop produksi dan harus dilakukan perbaikan maksimal 3X24 Jam. Reduce hingga pengendalian dilaksanakan sampai Rank C"
        ]);

        Rank::create([
            'rank_name' => 'C',
            'due_day' => 5,
            'rank_description' => "Potensi bahaya yang dapat menyebabkan kecelakaan ringan (first aid) dan harus dilakukan perbaikan maksimal 5X24 Jam."        
        ]);

        Factor::create([
            'factor_name' => 'MAN'
        ]);
        
        Factor::create([
            'factor_name' => 'MACHINE'
        ]);

        Factor::create([
            'factor_name' => 'MATERIAL'
        ]);

        Factor::create([
            'factor_name' => 'METHOD'
        ]);

        Factor::create([
            'factor_name' => 'ENVIRONMENT'
        ]);
    }
}
