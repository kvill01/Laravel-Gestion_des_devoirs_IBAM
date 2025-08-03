@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Liste des devoirs</h1>
        <a href="{{ route('devoirs.create') }}" class="btn btn-primary">Créer un devoir</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Cours</th>
                    <th>Semestre</th>
                    <th>Année Académique</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($devoirs as $devoir)
                    <tr>
                        <td>{{ $devoir->nom_devoir }}</td>
                        <td>{{ $devoir->cours->nom }}</td>
                        <td>{{ $devoir->semestre->nom }}</td>
                        <td>{{ $devoir->anneeAcademique->nom }}</td>
                        <td>
                            <span class="badge badge-{{ $devoir->statut === 'en_attente' ? 'warning' : ($devoir->statut === 'approuve' ? 'success' : 'danger') }}">
                                {{ ucfirst(str_replace('_', ' ', $devoir->statut)) }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('devoirs.edit', $devoir->id) }}" class="btn btn-sm btn-info">Modifier</a>
                                @if($devoir->fichier_sujet)
                                    <a href="{{ route('devoirs.download', $devoir->id) }}" class="btn btn-sm btn-secondary">Télécharger</a>
                                @endif
                                <form action="{{ route('devoirs.destroy', $devoir->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce devoir ?')">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Aucun devoir trouvé</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection