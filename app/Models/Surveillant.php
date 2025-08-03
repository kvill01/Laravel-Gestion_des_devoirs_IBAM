<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Devoirs;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Surveillant extends Model
{
    use HasFactory, SoftDeletes, Notifiable;

    protected $table = 'surveillants';

    protected $fillable = [
        'user_id',
        'nom',
        'prenom',
        'email',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function devoirs()
    {
        return $this->belongsToMany(Devoirs::class, 'devoir_surveillant', 'surveillant_id', 'devoir_id')
                    ->withPivot('statut', 'commentaire')
                    ->withTimestamps();
    }

    public function devoirsEnAttente()
    {
        return $this->devoirs()->wherePivot('statut', 'en_attente');
    }

    public function devoirsAcceptes()
    {
        return $this->devoirs()->wherePivot('statut', 'accepte');
    }

    public function devoirsRefuses()
    {
        return $this->devoirs()->wherePivot('statut', 'refuse');
    }
}