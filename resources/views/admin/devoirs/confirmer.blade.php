@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Confirmer et planifier le devoir') }}
    </h2>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800"></h1>
        <a href="{{ route('admin.devoirs.en_attente') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour aux devoirs en attente
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    @if($errors->has('general'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
        <p>{{ $errors->first('general') }}</p>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6">
        <div class="lg:col-span-8 space-y-6">
            <!-- Informations du devoir -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Informations du devoir
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-500">Nom du devoir:</p>
                        <p class="font-medium text-gray-800">{{ $devoir->nom_devoir }}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-500">Cours:</p>
                        <p class="font-medium text-gray-800">{{ $devoir->cours->intitule }}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-500">Enseignant:</p>
                        <p class="font-medium text-gray-800">{{ $devoir->enseignant->nom }} {{ $devoir->enseignant->prenom }}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-500">Durée:</p>
                        <p class="font-medium text-gray-800">{{ $devoir->duree_minutes }} minutes</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-500">Année académique:</p>
                        <p class="font-medium text-gray-800">{{ $devoir->anneeAcademique->libelle ?? date('Y').'-'.(date('Y')+1) }}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-500">Semestre:</p>
                        <p class="font-medium text-gray-800">{{ $devoir->semestre->libelle ?? 'S'.$devoir->semestre_id }}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-500">Type:</p>
                        <p class="font-medium text-gray-800">{{ $devoir->type }}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-500">Niveau:</p>
                        <p class="font-medium text-gray-800">{{ $devoir->niveau }}</p>
                    </div>
                </div>
                
                @if($devoir->sujet_devoir)
                <div class="mt-4">
                    <p class="text-sm text-gray-500 mb-1">Sujet du devoir:</p>
                    <div class="p-3 bg-gray-50 rounded-lg border border-gray-100">
                        <p class="text-gray-800">{{ $devoir->sujet_devoir }}</p>
                    </div>
                </div>
                @endif
                
                @if($devoir->fichier_sujet)
                <div class="mt-4">
                    <p class="text-sm text-gray-500 mb-1">Fichier du sujet:</p>
                    <a href="{{ route('admin.devoirs.download', $devoir) }}" class="inline-flex items-center mt-1 px-4 py-2 bg-blue-50 border border-blue-200 text-blue-700 rounded-md hover:bg-blue-100 transition-colors duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Télécharger le fichier
                    </a>
                </div>
                @endif
            </div>
            
            <!-- Formulaire de planification -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <form action="{{ route('admin.devoirs.confirmer', $devoir) }}" method="POST">
                    @csrf
                    
                    <h2 class="text-xl font-semibold mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Planification du devoir
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="date_heure" class="block text-sm font-medium text-gray-700 mb-1">Date et heure du devoir*</label>
                            <input type="datetime-local" name="date_heure" id="date_heure" class="form-input rounded-md shadow-sm mt-1 block w-full" 
                                value="{{ old('date_heure', $devoir->date_heure_proposee ? date('Y-m-d\TH:i', strtotime($devoir->date_heure_proposee)) : now()->format('Y-m-d\TH:i')) }}"
                                min="{{ now()->format('Y-m-d\TH:i') }}"
                                required>
                            <small class="text-gray-500">Format: JJ/MM/AAAA HH:MM</small>
                            @error('date_heure')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="salles" class="block text-sm font-medium text-gray-700 mb-1">Salles de composition*</label>
                            <select name="salles[]" id="salles" class="form-select rounded-md shadow-sm mt-1 block w-full" multiple required>
                                @foreach($salles as $salle)
                                    <option value="{{ $salle->id }}">{{ $salle->nom }} (Capacité: {{ $salle->capacite }} places)</option>
                                @endforeach
                            </select>
                            <small class="text-gray-500">Maintenez Ctrl (ou Cmd sur Mac) pour sélectionner plusieurs salles</small>
                            @error('salles')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="surveillants" class="block text-sm font-medium text-gray-700 mb-1">Surveillants*</label>
                        <div class="mt-1 bg-white rounded-md shadow-sm border border-gray-300 p-4 max-h-60 overflow-y-auto">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                @foreach($surveillants as $surveillant)
                                    <div class="flex items-center">
                                        <input type="checkbox" name="surveillants[]" id="surveillant_{{ $surveillant->id }}" value="{{ $surveillant->id }}" 
                                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        <label for="surveillant_{{ $surveillant->id }}" class="ml-2 block text-sm text-gray-900">
                                            {{ $surveillant->user->name }} {{ $surveillant->user->prenom }}
                                            @if($surveillant->contact)
                                                <span class="text-xs text-gray-500">({{ $surveillant->contact }})</span>
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Veuillez sélectionner au moins un surveillant pour le devoir</p>
                        @error('surveillants')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Confirmer et planifier le devoir
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Colonne de droite -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Proposition de l'enseignant -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Proposition de l'enseignant
                </h2>
                
                <div class="p-4 bg-blue-50 rounded-lg border border-blue-100 mb-4">
                    <p class="text-sm text-blue-700">
                        Vous êtes sur le point de confirmer ce devoir. Veuillez vérifier les informations et planifier la date, les salles et les surveillants.
                    </p>
                </div>
                
                @if($devoir->date_heure_proposee)
                    <div class="mb-4">
                        <p class="text-sm text-gray-500 mb-1">Date et heure proposées:</p>
                        <div class="flex items-center bg-yellow-50 p-2 rounded-md border border-yellow-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($devoir->date_heure_proposee)->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                @else
                    <div class="mb-4">
                        <div class="flex items-center bg-gray-50 p-2 rounded-md border border-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-gray-500 italic">Aucune date/heure proposée par l'enseignant</p>
                        </div>
                    </div>
                @endif
                
                @if($devoir->commentaire_enseignant)
                    <div class="mb-4">
                        <p class="text-sm text-gray-500 mb-1">Commentaires de l'enseignant:</p>
                        <div class="p-3 bg-gray-50 rounded-lg border border-gray-100">
                            <p class="text-gray-800 text-sm italic">{{ $devoir->commentaire_enseignant }}</p>
                        </div>
                    </div>
                @endif
                
                <!-- Aide à la décision -->
                <div class="mt-4">
                    <h3 class="font-medium text-gray-700 mb-2 text-sm">Conseils pour la planification</h3>
                    <ul class="text-sm text-gray-600 space-y-2">
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-green-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Vérifiez la disponibilité des salles sélectionnées
                        </li>
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-green-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Assurez-vous que les surveillants sont disponibles
                        </li>
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-green-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Prévoyez suffisamment de places pour tous les étudiants
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
