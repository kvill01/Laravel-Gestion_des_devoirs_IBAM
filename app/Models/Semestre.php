<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Semestre extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'semestres';
    
    protected $fillable = [
        'libelle',
        'programme_id',
        'annee_academique_id',
    ];
    
    // Relations
    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }
    
    public function anneeAcademique()
    {
        return $this->belongsTo(AnneeAcademique::class);
    }
    
    public function devoirs()
    {
        return $this->hasMany(Devoirs::class);
    }
}
