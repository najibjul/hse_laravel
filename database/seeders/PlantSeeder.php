<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Plant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plant = Plant::create([
            'plant_name' => 'Engineering'
        ]);

        Department::create([
            'department_name' => 'ENG',
            'plant_id' => $plant->id
        ]);

        Department::create([
            'department_name' => 'ENG A',
            'plant_id' => $plant->id
        ]);

        Department::create([
            'department_name' => 'ENG B',
            'plant_id' => $plant->id
        ]);

        Department::create([
            'department_name' => 'ENG R',
            'plant_id' => $plant->id
        ]);

        Department::create([
            'department_name' => 'ENG D',
            'plant_id' => $plant->id
        ]);

        Department::create([
            'department_name' => 'ENG M',
            'plant_id' => $plant->id
        ]);

        Department::create([
            'department_name' => 'ENG UTL',
            'plant_id' => $plant->id
        ]);

        Department::create([
            'department_name' => 'ENG INST',
            'plant_id' => $plant->id
        ]);

        Department::create([
            'department_name' => 'ENG GD. SPART',
            'plant_id' => $plant->id
        ]);

        Department::create([
            'department_name' => 'ENG WS',
            'plant_id' => $plant->id
        ]);

        Department::create([
            'department_name' => 'ENG ISTR',
            'plant_id' => $plant->id
        ]);

        Department::create([
            'department_name' => 'ENG MOLD S.',
            'plant_id' => $plant->id
        ]);
    }
}
