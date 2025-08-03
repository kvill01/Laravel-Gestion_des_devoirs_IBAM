<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Domaine;

class DomaineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $domaines = [
            ['nom' => 'Sciences et Technologies'],
            ['nom' => 'Sciences Economique et de Gestion'],
            ['nom' => 'Lettres, Langues et Arts'],
            ['nom' => 'Sciences Humaines et Sociales'],
            ['nom' => 'Sciences Politiques et Juridiques'],
        ];

        foreach ($domaines as $domaine) {
            Domaine::create($domaine);
        }

        $this->command->info('Domaines créés avec succès!');
    }
}