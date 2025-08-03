<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mention extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mentions';
    
    protected $fillable = [
        'nom',
        'niveau',
        'type',
        'domaine_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function domaine()
    {
        return $this->belongsTo(Domaine::class, 'domaine_id');
    }

    public function cours()
    {
        return $this->hasMany(Cours::class, 'mention_id');
    }

    public function programmes()
    {
        return $this->hasMany(Programme::class, 'mention_id');
    }
}