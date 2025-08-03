<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cours;
use App\Models\Enseignant;
use App\Models\Semestre;
use App\Models\AnneeAcademique;
use App\Models\Salle;
use App\Models\Surveillant;
use App\Models\User;
use App\Models\Soumission;
use App\Models\Filiere;
use App\Models\Niveau;
use Illuminate\Database\Eloquent\SoftDeletes;

class Devoirs extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'devoirs';

    protected $fillable = [
        'nom_devoir',
        'nom_cours',
        'code_QR',
        'statut',
        'enseignants_id',
        'fichier_sujet',
        'date_heure',
        'date_heure_proposee',
        'commentaire_enseignant',
        'duree_minutes',
        'type',
        'niveau',
        'cours_id',
        'semestre_id',
        'annee_academique_id',
        'salles_id',
        'filiere_id',
        'niveau_id',
    ];

    protected $casts = [
        'date_heure' => 'datetime',
        'date_heure_proposee' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function cours()
    {
        return $this->belongsTo(Cours::class, 'cours_id');
    }

    public function enseignant()
    {
        return $this->belongsTo(Enseignant::class, 'enseignants_id');
    }

    public function semestre()
    {
        return $this->belongsTo(Semestre::class, 'semestre_id');
    }

    public function anneeAcademique()
    {
        return $this->belongsTo(AnneeAcademique::class, 'annee_academique_id');
    }

    public function salle()
    {
        return $this->belongsTo(Salle::class, 'salles_id');
    }

    public function salles()
    {
        return $this->belongsToMany(Salle::class, 'devoir_salles', 'devoir_id', 'salle_id')
            ->withTimestamps();
    }

    public function surveillants()
    {
        return $this->belongsToMany(Surveillant::class, 'devoir_surveillant', 'devoir_id', 'surveillant_id')
            ->withPivot('statut', 'commentaire')
            ->withTimestamps();
    }

    public function surveillantsEnAttente()
    {
        return $this->surveillants()->wherePivot('statut', 'en_attente');
    }

    public function surveillantsAcceptes()
    {
        return $this->surveillants()->wherePivot('statut', 'accepte');
    }

    public function surveillantsRefuses()
    {
        return $this->surveillants()->wherePivot('statut', 'refuse');
    }

    public function soumissions()
    {
        return $this->hasMany(Soumission::class, 'devoir_id');
    }
    
    /**
     * Relation avec la filière
     */
    public function filiere()
    {
        return $this->belongsTo(Filiere::class, 'filiere_id');
    }
    
    /**
     * Relation avec le niveau
     */
    public function niveau()
    {
        return $this->belongsTo(Niveau::class, 'niveau_id');
    }
    
    /**
     * Vérifier si le devoir est planifié (a une date et salle assignées)
     */
    public function estPlanifie()
    {
        return $this->date_heure !== null && ($this->salles_id !== null || $this->salles()->count() > 0);
    }
    
    /**
     * Vérifier si le devoir a des surveillants assignés
     */
    public function aSurveillants()
    {
        return $this->surveillants()->count() > 0;
    }
}