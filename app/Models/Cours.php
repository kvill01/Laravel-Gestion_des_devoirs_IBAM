<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Enseignant;
use App\Models\Mention;
use App\Models\Devoirs;
use App\Models\User;
use App\Models\Filiere;
use App\Models\Niveau;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cours extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cours';

    protected $fillable = [
        'intitule',
        'description',
        'enseignants_id',
        'mention_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function enseignant()
    {
        return $this->belongsTo(Enseignant::class, 'enseignants_id');
    }
    
    public function enseignants()
    {
        return $this->belongsToMany(Enseignant::class, 'cours_enseignant', 'cours_id', 'enseignant_id');
    }

    public function mention()
    {
        return $this->belongsTo(Mention::class);
    }

    public function devoirs()
    {
        return $this->hasMany(Devoirs::class, 'cours_id');
    }
    
    /**
     * Les filières associées à ce cours via la relation many-to-many
     */
    public function filieres()
    {
        return $this->belongsToMany(Filiere::class, 'cours_filiere_niveau')
                    ->withPivot('niveau_id')
                    ->withTimestamps();
    }
    
    /**
     * Les niveaux associés à ce cours via la relation many-to-many
     */
    public function niveaux()
    {
        return $this->belongsToMany(Niveau::class, 'cours_filiere_niveau')
                    ->withPivot('filiere_id')
                    ->withTimestamps();
    }
    
    /**
     * Vérifie si ce cours est associé à une combinaison filière/niveau spécifique
     */
    public function estAssocieA($filiereCode, $niveauCode)
    {
        return $this->belongsToMany(Filiere::class, 'cours_filiere_niveau')
                    ->wherePivot('filiere_id', function($query) use ($filiereCode) {
                        $query->select('id')
                              ->from('filieres')
                              ->where('code', $filiereCode);
                    })
                    ->wherePivot('niveau_id', function($query) use ($niveauCode) {
                        $query->select('id')
                              ->from('niveaux')
                              ->where('code', $niveauCode);
                    })
                    ->exists();
    }
}