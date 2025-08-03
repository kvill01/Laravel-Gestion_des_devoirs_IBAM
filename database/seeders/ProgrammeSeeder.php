<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgrammeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programmes = [
            [
                'libelle' => 'Licence',
                'description' => 'Programme de Licence (Bac+3)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'libelle' => 'Master',
                'description' => 'Programme de Master (Bac+5)',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('programmes')->insert($programmes);
    }
}
