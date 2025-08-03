<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Etudiant;
use App\Models\Enseignant;
use App\Models\Surveillant;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'prenom',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Vérifie si l'utilisateur est un administrateur
     */
    public function isAdmin() : bool
    {
        return $this->role === 'admin';
    }

    /**
     * Vérifie si l'utilisateur est un étudiant
     */
    public function isEtudiant() : bool 
    {
        return $this->role === 'etudiant';
    }

    /**
     * Vérifie si l'utilisateur est un enseignant
     */
    public function isEnseignant() : bool
    {
        return $this->role === 'enseignant';
    }

    /**
     * Vérifie si l'utilisateur est un surveillant
     */
    public function isSurveillant() : bool
    {
        return $this->role === 'surveillant';
    }

    /**
     * Relation avec l'étudiant
     */
    public function etudiant()
    {
        return $this->hasOne(Etudiant::class);
    }

    /**
     * Relation avec l'enseignant
     */
    public function enseignant()
    {
        return $this->hasOne(Enseignant::class);
    }

    /**
     * Relation avec le surveillant
     */
    public function surveillant()
    {
        return $this->hasOne(Surveillant::class);
    }

    /**
     * Méthode pour obtenir les données spécifiques au rôle de l'utilisateur
     */
    public function getRoleData()
    {
        if ($this->isEtudiant()) {
            return $this->etudiant;
        } elseif ($this->isEnseignant()) {
            return $this->enseignant;
        } elseif ($this->isSurveillant()) {
            return $this->surveillant;
        }
        
        return null;
    }
}