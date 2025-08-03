@extends('layouts.admin')

@section('title', 'Liste des Cours')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Liste des cours</h6>
        <a href="{{ route('admin.cours.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Nouveau cours
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="coursTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Intitulé</th>
                        <th>Enseignant</th>
                        <th>Filières</th>
                        <th>Niveaux</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cours as $cours)
                    <tr>
                        <td>{{ $cours->intitule }}</td>
                        <td>{{ $cours->enseignant->nom }} {{ $cours->enseignant->prenom }}</td>
                        <td>
                            @foreach($cours->filieres as $filiere)
                                <span class="badge bg-info">{{ $filiere->code }}</span>
                            @endforeach
                        </td>
                        <td>
                            @foreach($cours->niveaux as $niveau)
                                <span class="badge bg-secondary">{{ $niveau->code }}</span>
                            @endforeach
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.cours.show', $cours) }}" class="btn btn-info btn-sm" title="Voir les détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.cours.edit', $cours) }}" class="btn btn-warning btn-sm" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.cours.destroy', $cours) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce cours ?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .sidebar {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        z-index: 100;
        padding: 48px 0 0;
        box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
    }
    
    .sidebar .nav-link {
        font-weight: 500;
        color: #333;
        padding: 0.5rem 1rem;
    }
    
    .sidebar .nav-link:hover {
        color: #007bff;
    }
    
    .sidebar .nav-link.active {
        color: #007bff;
    }
    
    .badge {
        margin-right: 0.25rem;
    }
</style>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#coursTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json'
            }
        });
    });
</script>
@endsection