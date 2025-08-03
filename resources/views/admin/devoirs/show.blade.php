@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Détails du devoir') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800"><h1>
            <div class="flex space-x-2">
                <a href="{{ route('admin.devoirs.en_attente') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Retour à la liste
                </a>
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Tableau de bord
                </a>
            </div>
        </div>

        <!-- Disposition principale en deux colonnes pour desktop -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6">
            <!-- Colonne principale - Informations du devoir -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Carte d'informations principales -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Informations du devoir
                        </h2>
                        <span class="px-3 py-1 rounded-full text-sm font-medium 
                            @if($devoir->statut == 'en_attente') bg-yellow-100 text-yellow-800
                            @elseif($devoir->statut == 'confirmé') bg-green-100 text-green-800
                            @elseif($devoir->statut == 'terminé') bg-blue-100 text-blue-800
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $devoir->statut)) }}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-3">
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <p class="text-sm text-gray-500">Cours</p>
                                <p class="font-medium text-gray-800">{{ $devoir->cours->intitule ?? $devoir->nom_cours }}</p>
                            </div>
                            
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <p class="text-sm text-gray-500">Enseignant</p>
                                <p class="font-medium text-gray-800">{{ $devoir->enseignant->nom }} {{ $devoir->enseignant->prenom }}</p>
                            </div>
                            
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <p class="text-sm text-gray-500">Date et heure</p>
                                <p class="font-medium text-green-600">{{ \Carbon\Carbon::parse($devoir->date_heure)->format('d/m/Y à H:i') }}</p>
                            </div>
                            
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <p class="text-sm text-gray-500">Durée</p>
                                <p class="font-medium text-gray-800">{{ $devoir->duree_minutes }} minutes</p>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <p class="text-sm text-gray-500">Année académique</p>
                                <p class="font-medium text-gray-800">{{ $devoir->anneeAcademique->libelle ?? date('Y').'-'.(date('Y')+1) }}</p>
                            </div>
                            
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <p class="text-sm text-gray-500">Semestre</p>
                                <p class="font-medium text-gray-800">{{ $devoir->semestre->libelle ?? 'S'.$devoir->semestre_id }}</p>
                            </div>
                            
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <p class="text-sm text-gray-500">Type</p>
                                <p class="font-medium text-gray-800">{{ $devoir->type }}</p>
                            </div>
                            
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <p class="text-sm text-gray-500">Niveau</p>
                                <p class="font-medium text-gray-800">{{ $devoir->niveau }}</p>
                            </div>
                        </div>
                    </div>
                    
                    @if($devoir->commentaire_enseignant)
                    <div class="mt-4 bg-indigo-50 p-4 rounded-lg border-l-4 border-indigo-400">
                        <h3 class="text-md font-medium text-indigo-800 mb-2 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                            Commentaire de l'enseignant
                        </h3>
                        <p class="text-indigo-700">{{ $devoir->commentaire_enseignant }}</p>
                    </div>
                    @endif
                    
                    @if($devoir->fichier_sujet)
                    <div class="mt-4">
                        <a href="{{ route('admin.devoirs.download', $devoir) }}" class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Télécharger le fichier du sujet
                        </a>
                    </div>
                    @endif
                </div>

                <!-- Carte des salles assignées -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Salles assignées
                    </h3>
                    
                    @if(count($devoir->salles) > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($devoir->salles as $salle)
                        <div class="bg-indigo-50 p-3 rounded-lg border border-indigo-100 hover:shadow-md transition-shadow">
                            <p class="font-medium text-indigo-700">{{ $salle->nom }}</p>
                            <p class="text-sm text-gray-600">Capacité: {{ $salle->capacite }} places</p>
                            @if($salle->localisation)
                            <p class="text-sm text-gray-600">Localisation: {{ $salle->localisation }}</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="bg-gray-50 p-4 rounded-md text-center">
                        <p class="text-gray-500">Aucune salle n'a encore été assignée.</p>
                    </div>
                    @endif
                </div>
                
                <!-- Carte des surveillants assignés -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                        Surveillants assignés
                    </h3>
                    
                    @if(count($devoir->surveillants) > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($devoir->surveillants as $surveillant)
                        <div class="bg-purple-50 p-3 rounded-lg border border-purple-100 hover:shadow-md transition-shadow">
                            <p class="font-medium text-purple-700">{{ $surveillant->user->name }} {{ $surveillant->user->prenom }}</p>
                            <p class="text-sm">
                                @if($surveillant->pivot->statut == 'en_attente')
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">En attente</span>
                                @elseif($surveillant->pivot->statut == 'accepte')
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Accepté</span>
                                @elseif($surveillant->pivot->statut == 'refuse')
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Refusé</span>
                                @endif
                            </p>
                            @if($surveillant->pivot->commentaire)
                            <p class="text-sm text-gray-600 mt-1 italic">{{ $surveillant->pivot->commentaire }}</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="bg-gray-50 p-4 rounded-md text-center">
                        <p class="text-gray-500">Aucun surveillant n'a encore été assigné.</p>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Colonne secondaire - Actions -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Carte des actions principales -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Actions
                    </h2>
                    
                    <div class="space-y-4">
                        @if($devoir->statut == 'en_attente')
                        <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-100 mb-4">
                            <p class="text-sm text-yellow-700 mb-2">Ce devoir est en attente de confirmation. Vous pouvez le confirmer pour permettre aux étudiants d'y accéder.</p>
                        </div>
                        <a href="{{ route('admin.devoirs.confirmer', $devoir) }}" class="w-full inline-flex justify-center items-center px-4 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-sm text-white tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-800 focus:ring ring-green-300 disabled:opacity-25 transition-all duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Confirmer le devoir
                        </a>
                        @endif
                        
                        @if($devoir->statut == 'confirmé')
                        <div class="p-4 bg-green-50 rounded-lg border border-green-100 mb-4">
                            <p class="text-sm text-green-700 mb-2">Ce devoir est confirmé. Vous pouvez le marquer comme terminé une fois l'examen achevé.</p>
                        </div>
                        <form action="{{ route('admin.devoirs.terminer', $devoir) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300 disabled:opacity-25 transition-all duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Marquer comme terminé
                            </button>
                        </form>
                        @endif

                        @if($devoir->statut == 'terminé')
                        <div class="p-4 bg-blue-50 rounded-lg border border-blue-100 mb-4">
                            <p class="text-sm text-blue-700">Ce devoir est terminé. Toutes les actions administratives ont été complétées.</p>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Carte de gestion -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-medium text-gray-700 mb-3 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                        </svg>
                        Gestion
                    </h3>
                    
                    <div class="space-y-3">
                        <a href="{{ route('admin.devoirs.edit', $devoir) }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-50 border border-indigo-200 rounded-md font-medium text-sm text-indigo-700 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Modifier le devoir
                        </a>
                        
                        <button type="button" class="w-full inline-flex justify-center items-center px-4 py-2 bg-purple-50 border border-purple-200 rounded-md font-medium text-sm text-purple-700 hover:bg-purple-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Gérer les salles
                        </button>
                        
                        <button type="button" class="w-full inline-flex justify-center items-center px-4 py-2 bg-teal-50 border border-teal-200 rounded-md font-medium text-sm text-teal-700 hover:bg-teal-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Gérer les surveillants
                        </button>
                    </div>
                </div>
                
                @if($devoir->statut == 'confirmé' && $devoir->code_QR)
                <!-- Carte du code QR -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-medium text-gray-700 mb-3 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                        Code QR du devoir
                    </h3>
                    <div class="flex flex-col items-center">
                        <div class="p-2 bg-white border rounded shadow-sm mb-3">
                            <img src="{{ asset('qrcodes/' . $devoir->code_QR) }}" alt="Code QR du devoir" class="w-48 h-48">
                        </div>
                        <a href="{{ asset('qrcodes/' . $devoir->code_QR) }}" download class="inline-flex items-center px-3 py-2 bg-indigo-100 text-indigo-700 rounded-md text-sm hover:bg-indigo-200 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Télécharger le code QR
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
