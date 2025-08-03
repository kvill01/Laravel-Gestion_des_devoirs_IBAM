<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnneeAcademique extends Model
{
    protected $table = 'annees_academiques';
    
    protected $fillable = [
        'annee_debut',
        'annee_fin',
    ];
    
    /**
     * Accesseur pour obtenir l'année académique formatée
     *
     * @return string
     */
    public function getAnneeAttribute()
    {
        return $this->annee_debut . '-' . $this->annee_fin;
    }
}
