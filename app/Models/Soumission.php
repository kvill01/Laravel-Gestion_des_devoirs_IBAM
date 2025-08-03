<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Soumission extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'soumissions';

    protected $fillable = [
        'devoir_id',
        'etudiant_id',
        'fichier_soumis',
        'note',
        'commentaire',
        'date_soumission',
    ];

    protected $casts = [
        'date_soumission' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'note' => 'float',
    ];

    public function devoir()
    {
        return $this->belongsTo(Devoirs::class, 'devoir_id');
    }

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class, 'etudiant_id');
    }
}
