<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'niveau',
        'annee_scolaire'
    ];

    public function devoirs()
    {
        return $this->hasMany(Devoir::class, 'classes_id');
    }

    public function etudiants()
    {
        return $this->hasMany(Etudiant::class, 'classes_id');
    }
} 