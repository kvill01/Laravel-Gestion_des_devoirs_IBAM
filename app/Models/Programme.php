<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Programme extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'libelle',
        'description',
        'duree_annees', // Nouveau champ pour la durée en années
        'filiere_id',   // Nouveau champ pour lier à une filière
    ];
    
    /**
     * Récupère les semestres associés à ce programme
     */
    public function semestres()
    {
        return $this->hasMany(Semestre::class);
    }
    
    /**
     * Récupère la filière associée à ce programme
     */
    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }
    
    /**
     * Récupère les étudiants inscrits à ce programme
     */
    public function etudiants()
    {
        return $this->hasMany(Etudiant::class);
    }
    
    /**
     * Récupère les cours associés à ce programme
     */
    public function cours()
    {
        return $this->belongsToMany(Cours::class, 'cours_programme', 'programme_id', 'cours_id')
                    ->withTimestamps();
    }
}
