<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\AnneeAcademique;
use App\Models\Devoirs;

class Etudiant extends Model
{
    use HasFactory;

    protected $table = 'etudiants';

    protected $fillable = [
        'user_id',
        'name',
        'prenom',
        'date_naissance',
        'filiere_id',
        'niveau_id',
        'annee_academique_id',
        'created_at',
        'updated_at',
    ];


    /**
     * Récupérer l'utilisateur associé
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Récupérer la filière associée
     */
    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }
    
    /**
     * Récupérer le niveau associé
     */
    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }
    
    /**
     * Récupérer l'année académique associée
     */
    public function anneeAcademique()
    {
        return $this->belongsTo(AnneeAcademique::class);
    }
    
    /**
     * Obtenir tous les devoirs associés à la filière et au niveau de l'étudiant
     */
    public function devoirs()
    {
        return Devoirs::whereHas('cours', function($query) {
            $query->whereHas('filieres', function($subQuery) {
                $subQuery->where('filieres.id', $this->filiere_id)
                         ->whereHas('niveaux', function($niveauQuery) {
                             $niveauQuery->where('niveaux.id', $this->niveau_id);
                         });
            });
        });
    }
}