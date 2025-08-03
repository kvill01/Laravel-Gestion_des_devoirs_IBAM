@extends('layouts.admin')

@section('title', 'Détails du Cours')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Informations du cours</h6>
                <div>
                    <a href="{{ route('admin.cours.edit', $cours) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                    <a href="{{ route('admin.cours.index') }}" class="btn btn-secondary btn-sm ml-2">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="mb-4">{{ $cours->intitule }}</h4>
                        
                        <div class="mb-4">
                            <h5 class="text-primary">Description</h5>
                            <p class="text-muted">{{ $cours->description }}</p>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5 class="text-primary">Enseignant</h5>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-primary text-white me-2">
                                        {{ substr($cours->enseignant->prenom, 0, 1) }}{{ substr($cours->enseignant->nom, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="mb-0">{{ $cours->enseignant->nom }} {{ $cours->enseignant->prenom }}</p>
                                        <small class="text-muted">{{ $cours->enseignant->specialite ?? 'Non spécifié' }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5 class="text-primary">Statistiques</h5>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="card bg-light">
                                            <div class="card-body text-center">
                                                <h6 class="card-title mb-0">{{ $cours->filieres->count() }}</h6>
                                                <small class="text-muted">Filières</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card bg-light">
                                            <div class="card-body text-center">
                                                <h6 class="card-title mb-0">{{ $cours->niveaux->count() }}</h6>
                                                <small class="text-muted">Niveaux</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="m-0 font-weight-bold text-primary">Filières associées</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="list-group">
                                            @foreach($cours->filieres as $filiere)
                                                <div class="list-group-item">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h6 class="mb-0">{{ $filiere->code }}</h6>
                                                            <small class="text-muted">{{ $filiere->nom }}</small>
                                                        </div>
                                                        <span class="badge bg-primary">{{ $filiere->niveaux->count() }} niveaux</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="m-0 font-weight-bold text-primary">Niveaux associés</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="list-group">
                                            @foreach($cours->niveaux as $niveau)
                                                <div class="list-group-item">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h6 class="mb-0">{{ $niveau->code }}</h6>
                                                            <small class="text-muted">{{ $niveau->nom }}</small>
                                                        </div>
                                                        <span class="badge bg-info">{{ $niveau->filieres->count() }} filières</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Actions rapides</h6>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <a href="{{ route('admin.cours.edit', $cours) }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-edit text-warning"></i> Modifier le cours
                    </a>
                    <a href="#" class="list-group-item list-group-item-action" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="fas fa-trash text-danger"></i> Supprimer le cours
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <i class="fas fa-file-pdf text-danger"></i> Générer le PDF
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <i class="fas fa-calendar-alt text-info"></i> Voir l'emploi du temps
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Informations supplémentaires</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="text-primary">Date de création</h6>
                    <p class="mb-0">{{ $cours->created_at->format('d/m/Y H:i') }}</p>
                </div>
                
                <div class="mb-3">
                    <h6 class="text-primary">Dernière modification</h6>
                    <p class="mb-0">{{ $cours->updated_at->format('d/m/Y H:i') }}</p>
                </div>
                
                <div>
                    <h6 class="text-primary">Statut</h6>
                    <span class="badge bg-success">Actif</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">Confirmation de suppression</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer le cours <strong>{{ $cours->intitule }}</strong> ?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i> Cette action est irréversible.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('admin.cours.destroy', $cours) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }
    
    .list-group-item {
        border-left: none;
        border-right: none;
    }
    
    .list-group-item:first-child {
        border-top: none;
    }
    
    .list-group-item:last-child {
        border-bottom: none;
    }
    
    .list-group-item i {
        width: 20px;
        text-align: center;
        margin-right: 10px;
    }
</style>
@endsection
