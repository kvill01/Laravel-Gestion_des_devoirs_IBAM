<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Enseignant;
use App\Models\Domaine;

class EnseignantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vérifier si nous avons des domaines
        $domaineId = Domaine::first() ? Domaine::first()->id : 1;

        // Créer quelques enseignants pour les tests
        $enseignants = [
            [
                'nom' => 'Guinko',
                'prenom' => 'Ferdinand',
                'email' => 'ferdinand@guinko.com',
                'grade' => 'Maitre de conférences',
            ],
            [
                'nom' => 'Ouattara',
                'prenom' => 'Yacouba',
                'email' => 'yacouba@ouattara.com',
                'grade' => 'Professeur',
            ],
            [
                'nom' => 'Dabone',
                'prenom' => 'Yamba',
                'email' => 'yamba@dabone.com',
                'grade' => 'Professeur',
            ],
        ];

        foreach ($enseignants as $enseignantData) {
            // Créer l'utilisateur
            $user = User::create([
                'name' => $enseignantData['nom'],
                'prenom' => $enseignantData['prenom'],
                'email' => $enseignantData['email'],
                'password' => Hash::make('password'),
                'role' => 'enseignant',
                'email_verified_at' => now(),
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ]);

            // Créer l'enseignant associé
            Enseignant::create([
                'user_id' => $user->id,
                'domaine_id' => $domaineId,
                'nom' => $enseignantData['nom'],
                'prenom' => $enseignantData['prenom'],
                'email' => $enseignantData['email'],
                'grade' => $enseignantData['grade'],
                'created_at' => now(),
                'updated_at' => now(),  
                'deleted_at' => null,
            ]);
        }

        $this->command->info('Enseignants créés avec succès!');
    }
}