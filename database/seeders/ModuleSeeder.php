<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            // [
            //     'module'      => 'Home',
            //     'description' => 'Modulo principal',
            //     'icon'  => 'mdi-home',
            //     'name'  => 'home',
            //     'order' => 1,
            //     'status' => 1,
            //     'permission_id' => 1
            // ],
        ];

        // Utiliza un bucle foreach para insertar cada conjunto de datos
        foreach ($modules as $data) {
            Module::create($data);
        }

    }
}
