<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Salle extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'salles';

    protected $fillable = [
        'nom',
        'capacite',
        'type',
        'localisation',
        'disponible',
        'description'
    ];

    protected $casts = [
        'disponible' => 'boolean',
        'capacite' => 'integer'
    ];

    public function devoirs()
    {
        return $this->belongsToMany(Devoirs::class, 'devoir_salles', 'salle_id', 'devoir_id');
    }
}
