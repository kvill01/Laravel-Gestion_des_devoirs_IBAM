<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Niveau extends Model
{
    use HasFactory;

    protected $table = 'niveaux';

    protected $fillable = [
        'code',
        'nom',
        'description',
    ];

    /**
     * Les cours associés à ce niveau via la relation many-to-many
     */
    public function cours()
    {
        return $this->belongsToMany(Cours::class, 'cours_filiere_niveau')
                    ->withPivot('filiere_id')
                    ->withTimestamps();
    }

    /**
     * Les filières associées à ce niveau via la relation many-to-many
     */
    public function filieres()
    {
        return $this->belongsToMany(Filiere::class, 'cours_filiere_niveau')
                    ->withPivot('cours_id')
                    ->withTimestamps();
    }
}
