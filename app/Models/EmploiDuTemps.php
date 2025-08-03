<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmploiDuTemps extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'emploi_du_temps';
    
    protected $fillable = [
        'filiere_id',
        'niveau_id',
        'semestre_id',
        'cours_id',
        'enseignants_id',
        'salle_id',
        'jour',
        'heure_debut',
        'heure_fin',
        'type_cours',
        'date_debut',
        'date_fin',
        'commentaire',
    ];
    
    protected $casts = [
        'heure_debut' => 'datetime:H:i',
        'heure_fin' => 'datetime:H:i',
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];
    
    /**
     * Récupère la filière associée à cet emploi du temps
     */
    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }
    
    /**
     * Récupère le niveau associé à cet emploi du temps
     */
    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }
    
    /**
     * Récupère le semestre associé à cet emploi du temps
     */
    public function semestre()
    {
        return $this->belongsTo(Semestre::class);
    }
    
    /**
     * Récupère le cours associé à cet emploi du temps
     */
    public function cours()
    {
        return $this->belongsTo(Cours::class);
    }
    
    /**
     * Récupère l'enseignant associé à cet emploi du temps
     */
    public function enseignant()
    {
        return $this->belongsTo(Enseignant::class, 'enseignants_id');
    }
    
    /**
     * Récupère la salle associée à cet emploi du temps
     */
    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }
    
    /**
     * Scope pour filtrer les emplois du temps par semaine
     */
    public function scopeForWeek($query, $dateDebut, $dateFin)
    {
        return $query->where(function($q) use ($dateDebut, $dateFin) {
            $q->whereBetween('date_debut', [$dateDebut, $dateFin])
              ->orWhereBetween('date_fin', [$dateDebut, $dateFin])
              ->orWhere(function($subQ) use ($dateDebut, $dateFin) {
                  $subQ->where('date_debut', '<=', $dateDebut)
                       ->where('date_fin', '>=', $dateFin);
              });
        });
    }
}
