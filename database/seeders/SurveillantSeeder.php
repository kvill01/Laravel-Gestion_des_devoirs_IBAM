<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Surveillant;

class SurveillantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer quelques surveillants pour les tests
        $surveillants = [
            [
                'nom' => 'Petit',
                'prenom' => 'Marie',
                'email' => 'marie@petit.com',
            ],
            [
                'nom' => 'Guene',
                'prenom' => 'Moko',
                'email' => 'moko@guene.com',
            ],
        ];

        foreach ($surveillants as $surveillantData) {
            // Créer l'utilisateur
            $user = User::create([
                'name' => $surveillantData['nom'],
                'prenom' => $surveillantData['prenom'],
                'email' => $surveillantData['email'],
                'password' => Hash::make('password'),
                'role' => 'surveillant',
                'email_verified_at' => now(),
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),  
                'deleted_at' => null,
            ]);

            // Créer le surveillant associé
            Surveillant::create([
                'user_id' => $user->id,
                'nom' => $surveillantData['nom'],
                'prenom' => $surveillantData['prenom'],
                'email' => $surveillantData['email'],
                'created_at' => now(),
                'updated_at' => now(),  
                'deleted_at' => null,
            ]);
        }

        $this->command->info('Surveillants créés avec succès!');
    }
}