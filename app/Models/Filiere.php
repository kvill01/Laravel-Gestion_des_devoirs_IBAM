<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filiere extends Model
{
    use HasFactory;

    protected $table = 'filieres';

    protected $fillable = [
        'code',
        'nom',
        'description',
    ];

    /**
     * Les cours associés à cette filière via la relation many-to-many
     */
    public function cours()
    {
        return $this->belongsToMany(Cours::class, 'cours_filiere_niveau')
                    ->withPivot('niveau_id')
                    ->withTimestamps();
    }

    /**
     * Les niveaux associés à cette filière via la relation many-to-many
     */
    public function niveaux()
    {
        return $this->belongsToMany(Niveau::class, 'cours_filiere_niveau')
                    ->withPivot('cours_id')
                    ->withTimestamps();
    }
}
