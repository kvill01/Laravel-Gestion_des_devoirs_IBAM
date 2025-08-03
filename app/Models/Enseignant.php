<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Cours;
use App\Models\Devoir;
use App\Models\Domaine;

class Enseignant extends Model
{
    use HasFactory;

    protected $table = 'enseignants';

    protected $fillable = [
        'user_id',
        'domaine_id',
        'nom',
        'prenom',
        'email',
        'grade',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function domaine()
    {
        return $this->belongsTo(Domaine::class);
    }

    public function cours()
    {
        return $this->belongsToMany(Cours::class, 'cours_enseignant', 'enseignant_id', 'cours_id');
    }

    public function devoirs()
    {
        return $this->hasMany(Devoir::class, 'enseignants_id');
    }
}