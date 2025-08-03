@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Détails du devoir</h1>
        <div class="flex space-x-3">
            @if($devoir->statut !== 'confirmé' && $devoir->statut !== 'terminé')
            <a href="{{ route('enseignant.devoirs.edit', $devoir) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Modifier
            </a>
            @endif
            <a href="{{ route('enseignant.devoirs.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour aux devoirs
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
        <p>{{ session('error') }}</p>
    </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <!-- Entête avec statut -->
        <div class="bg-gray-50 p-6 border-b">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">{{ $devoir->nom_devoir }}</h2>
                    <p class="text-sm text-gray-600 mt-1">{{ $devoir->nom_cours }}</p>
                </div>
                <div>
                    @if($devoir->statut === 'en_attente')
                        <span class="px-4 py-2 inline-flex text-sm font-medium rounded-full bg-yellow-100 text-yellow-800">En attente de confirmation</span>
                    @elseif($devoir->statut === 'approuve')
                        <span class="px-4 py-2 inline-flex text-sm font-medium rounded-full bg-blue-100 text-blue-800">Approuvé</span>
                    @elseif($devoir->statut === 'confirmé')
                        <span class="px-4 py-2 inline-flex text-sm font-medium rounded-full bg-green-100 text-green-800">Confirmé</span>
                    @elseif($devoir->statut === 'terminé')
                        <span class="px-4 py-2 inline-flex text-sm font-medium rounded-full bg-purple-100 text-purple-800">Terminé</span>
                    @elseif($devoir->statut === 'refuse')
                        <span class="px-4 py-2 inline-flex text-sm font-medium rounded-full bg-red-100 text-red-800">Refusé</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="p-6">
            <!-- Informations générales -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-800 mb-4 pb-2 border-b">Informations générales</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Filière</p>
                        <p class="mt-1">
                            @if($devoir->filiere_id && $devoir->filiere)
                                {{ $devoir->filiere->nom }} ({{ $devoir->filiere->code }})
                            @else
                                {{ $devoir->type }}
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Niveau</p>
                        <p class="mt-1">
                            @if($devoir->niveau_id && $devoir->niveau)
                                {{ $devoir->niveau->nom }} ({{ $devoir->niveau->code }})
                            @else
                                {{ $devoir->niveau }}
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Année académique</p>
                        <p class="mt-1">
                            @if($devoir->anneeAcademique)
                                {{ $devoir->anneeAcademique->annee_debut }}-{{ $devoir->anneeAcademique->annee_fin }}
                            @else
                                Non spécifiée
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Semestre</p>
                        <p class="mt-1">{{ $devoir->semestre_id ? 'Semestre ' . $devoir->semestre_id : 'Non spécifié' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Durée</p>
                        <p class="mt-1">{{ $devoir->duree_minutes }} minutes</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Enseignant</p>
                        <p class="mt-1">{{ $devoir->enseignant->user->name }}</p>
                    </div>
                </div>
            </div>

            <!-- Planification -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-800 mb-4 pb-2 border-b">Planification</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Date et heure proposées</p>
                        <p class="mt-1">
                            @if($devoir->date_heure_proposee)
                                {{ \Carbon\Carbon::parse($devoir->date_heure_proposee)->format('d/m/Y H:i') }}
                            @else
                                Non spécifiées
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Date et heure de l'examen</p>
                        <p class="mt-1">
                            @if($devoir->date_heure)
                                {{ \Carbon\Carbon::parse($devoir->date_heure)->format('d/m/Y H:i') }}
                            @else
                                <span class="text-yellow-600">Non planifié</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Salles assignées -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-800 mb-4 pb-2 border-b">Salles assignées</h3>
                @if($devoir->salles->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($devoir->salles as $salle)
                            <div class="bg-gray-50 rounded-lg p-4 border">
                                <p class="font-medium">{{ $salle->nom }}</p>
                                <p class="text-sm text-gray-600">Capacité: {{ $salle->capacite }} places</p>
                                @if($salle->description)
                                    <p class="text-sm text-gray-600 mt-1">{{ $salle->description }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-600 italic">Aucune salle n'a encore été assignée à ce devoir.</p>
                @endif
            </div>

            <!-- Surveillants assignés -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-800 mb-4 pb-2 border-b">Surveillants assignés</h3>
                @if($devoir->surveillants->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($devoir->surveillants as $surveillant)
                            <div class="bg-gray-50 rounded-lg p-4 border">
                                <p class="font-medium">{{ $surveillant->prenom }} {{ $surveillant->nom }}</p>
                                <p class="text-sm text-gray-600">{{ $surveillant->email }}</p>
                                @if($surveillant->pivot && $surveillant->pivot->statut)
                                    <p class="text-sm mt-2">
                                        @if($surveillant->pivot->statut === 'confirme')
                                            <span class="px-2 py-1 inline-flex text-xs font-medium rounded-full bg-green-100 text-green-800">Confirmé</span>
                                        @elseif($surveillant->pivot->statut === 'en_attente')
                                            <span class="px-2 py-1 inline-flex text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">En attente</span>
                                        @elseif($surveillant->pivot->statut === 'refuse')
                                            <span class="px-2 py-1 inline-flex text-xs font-medium rounded-full bg-red-100 text-red-800">Refusé</span>
                                        @endif
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-600 italic">Aucun surveillant n'a encore été assigné à ce devoir.</p>
                @endif
            </div>

            <!-- Document du sujet -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-800 mb-4 pb-2 border-b">Document</h3>
                @if($devoir->fichier_sujet)
                    <a href="{{ asset('storage/devoirs/' . $devoir->fichier_sujet) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-medium text-sm text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Voir le sujet
                    </a>
                @else
                    <p class="text-gray-600 italic">Aucun document n'a été associé à ce devoir.</p>
                @endif
            </div>

            <!-- Commentaires -->
            <div>
                <h3 class="text-lg font-medium text-gray-800 mb-4 pb-2 border-b">Commentaires</h3>
                
                <div class="mb-4">
                    <p class="text-sm font-medium text-gray-500">Commentaire de l'enseignant</p>
                    <p class="mt-1 text-gray-700">{{ $devoir->commentaire_enseignant ?? 'Aucun commentaire' }}</p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">Commentaire de l'administration</p>
                    <p class="mt-1 text-gray-700">{{ $devoir->commentaire_admin ?? 'Aucun commentaire' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
