@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Modifier le devoir</h1>
        <a href="{{ route('enseignant.devoirs.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
            Retour aux devoirs
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
        <p class="font-bold">Veuillez corriger les erreurs suivantes :</p>
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Informations de base du devoir -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4 flex items-center text-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                    </svg>
                    Informations générales
                </h2>
                
                <form action="{{ route('enseignant.devoirs.update', $devoir->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="nom_devoir" class="block text-sm font-medium text-gray-700 mb-1">Nom du devoir*</label>
                            <input type="text" name="nom_devoir" id="nom_devoir" class="form-input rounded-md shadow-sm mt-1 block w-full" value="{{ old('nom_devoir', $devoir->nom_devoir) }}" required>
                            <small class="text-gray-500">Exemple: Contrôle continu 1, Examen final, etc.</small>
                        </div>

                        <div>
                            <label for="cours_id" class="block text-sm font-medium text-gray-700 mb-1">Cours*</label>
                            <select name="cours_id" id="cours_id" class="form-select rounded-md shadow-sm mt-1 block w-full" required>
                                <option value="">Sélectionner un cours</option>
                                @foreach($cours as $c)
                                <option value="{{ $c->id }}" {{ old('cours_id', $devoir->cours_id) == $c->id ? 'selected' : '' }}>{{ $c->intitule }}</option>
                                @endforeach
                            </select>
                            <small class="text-gray-500">Le cours auquel ce devoir est associé</small>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label for="filiere_id" class="block text-sm font-medium text-gray-700 mb-1">Filière*</label>
                            <select name="filiere_id" id="filiere_id" class="form-select rounded-md shadow-sm mt-1 block w-full" required>
                                <option value="">Sélectionner une filière</option>
                                @foreach($filieres as $filiere)
                                <option value="{{ $filiere->id }}" {{ old('filiere_id', $devoir->filiere_id) == $filiere->id ? 'selected' : '' }}>{{ $filiere->nom }} ({{ $filiere->code }})</option>
                                @endforeach
                            </select>
                            <small class="text-gray-500">La filière concernée par ce devoir</small>
                        </div>

                        <div>
                            <label for="niveau_id" class="block text-sm font-medium text-gray-700 mb-1">Niveau*</label>
                            <select name="niveau_id" id="niveau_id" class="form-select rounded-md shadow-sm mt-1 block w-full" required>
                                <option value="">Sélectionner un niveau</option>
                                @foreach($niveaux as $niveau)
                                <option value="{{ $niveau->id }}" {{ old('niveau_id', $devoir->niveau_id) == $niveau->id ? 'selected' : '' }}>{{ $niveau->nom }} ({{ $niveau->code }})</option>
                                @endforeach
                            </select>
                            <small class="text-gray-500">Le niveau concerné par ce devoir</small>
                        </div>

                        <div>
                            <label for="annee_academique_id" class="block text-sm font-medium text-gray-700 mb-1">Année académique*</label>
                            <select name="annee_academique_id" id="annee_academique_id" class="form-select rounded-md shadow-sm mt-1 block w-full" required>
                                <option value="">Sélectionner une année</option>
                                @foreach($annees as $annee)
                                <option value="{{ $annee->id }}" {{ old('annee_academique_id', $devoir->annee_academique_id) == $annee->id ? 'selected' : '' }}>{{ $annee->annee_debut }}-{{ $annee->annee_fin }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="semestre_id" class="block text-sm font-medium text-gray-700 mb-1">Semestre (optionnel)</label>
                            <select name="semestre_id" id="semestre_id" class="form-select rounded-md shadow-sm mt-1 block w-full">
                                <option value="">Sélectionner un semestre</option>
                                <option value="1" {{ old('semestre_id', $devoir->semestre_id) == 1 ? 'selected' : '' }}>Semestre 1</option>
                                <option value="2" {{ old('semestre_id', $devoir->semestre_id) == 2 ? 'selected' : '' }}>Semestre 2</option>
                            </select>
                        </div>

                        <div>
                            <label for="duree_minutes" class="block text-sm font-medium text-gray-700 mb-1">Durée (en minutes)*</label>
                            <input type="number" name="duree_minutes" id="duree_minutes" class="form-input rounded-md shadow-sm mt-1 block w-full" value="{{ old('duree_minutes', $devoir->duree_minutes) }}" min="15" max="240" required>
                            <small class="text-gray-500">Durée maximale: 240 minutes (4 heures)</small>
                        </div>
                    </div>

                    <div>
                        <label for="fichier_sujet" class="block text-sm font-medium text-gray-700 mb-1">Fichier du sujet (PDF, DOC, DOCX)</label>
                        <input type="file" name="fichier_sujet" id="fichier_sujet" class="form-input rounded-md shadow-sm mt-1 block w-full" accept=".pdf,.doc,.docx">
                        @if($devoir->fichier_sujet)
                            <small class="text-gray-500">Fichier actuel: {{ $devoir->fichier_sujet }} (laissez vide pour conserver)</small>
                        @else
                            <small class="text-gray-500">Taille maximale: 5 Mo</small>
                        @endif
                    </div>
            </div>
        </div>

        <!-- Proposition de date et commentaires -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4 flex items-center text-blue-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                </svg>
                Proposition d'horaire
            </h2>

            <div class="grid grid-cols-1 gap-4 mb-6">
                <div>
                    <label for="date_proposee" class="block text-sm font-medium text-gray-700 mb-1">Date proposée</label>
                    @php
                        $date_proposee = null;
                        if (old('date_proposee')) {
                            $date_proposee = old('date_proposee');
                        } elseif ($devoir->date_heure_proposee) {
                            $date_proposee = \Carbon\Carbon::parse($devoir->date_heure_proposee)->format('Y-m-d');
                        }
                    @endphp
                    <input type="date" name="date_proposee" id="date_proposee" class="form-input rounded-md shadow-sm mt-1 block w-full" value="{{ $date_proposee }}" min="{{ date('Y-m-d') }}">
                    <small class="text-gray-500">Proposition de date pour l'administration</small>
                </div>

                <div>
                    <label for="heure_proposee" class="block text-sm font-medium text-gray-700 mb-1">Heure proposée</label>
                    @php
                        $heure_proposee = null;
                        if (old('heure_proposee')) {
                            $heure_proposee = old('heure_proposee');
                        } elseif ($devoir->date_heure_proposee) {
                            $heure_proposee = \Carbon\Carbon::parse($devoir->date_heure_proposee)->format('H:i');
                        }
                    @endphp
                    <input type="time" name="heure_proposee" id="heure_proposee" class="form-input rounded-md shadow-sm mt-1 block w-full" value="{{ $heure_proposee }}">
                    <small class="text-gray-500">Proposition d'heure pour l'administration</small>
                </div>

                <div>
                    <label for="commentaire" class="block text-sm font-medium text-gray-700 mb-1">Commentaires pour l'administration</label>
                    <textarea name="commentaire" id="commentaire" rows="3" class="form-textarea rounded-md shadow-sm mt-1 block w-full">{{ old('commentaire', $devoir->commentaire_enseignant) }}</textarea>
                    <small class="text-gray-500">Précisions ou demandes particulières pour l'administration</small>
                </div>
            </div>

            <div class="bg-yellow-50 p-4 rounded-md border border-yellow-200">
                <h3 class="text-yellow-700 font-medium mb-2 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    Information importante
                </h3>
                <p class="text-sm text-yellow-600">
                    Vous pouvez proposer une date et une heure qui vous conviendraient, mais la décision finale appartient à l'administration.
                </p>
            </div>
        </div>
    </div>

    <!-- Section du devoir planifié (seulement si déjà planifié) -->
    @if($devoir->date_heure || $devoir->salles_id)
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4 flex items-center text-green-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            Détails de la planification
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
            @if($devoir->date_heure)
            <div class="bg-green-50 p-4 rounded-md">
                <h3 class="text-sm font-medium text-green-800 mb-1">Date et heure confirmées</h3>
                <p class="text-base font-semibold">{{ \Carbon\Carbon::parse($devoir->date_heure)->format('d/m/Y à H:i') }}</p>
            </div>
            @endif

            @if($devoir->salle)
            <div class="bg-blue-50 p-4 rounded-md">
                <h3 class="text-sm font-medium text-blue-800 mb-1">Salle assignée</h3>
                <p class="text-base font-semibold">{{ $devoir->salle->nom }} ({{ $devoir->salle->capacite }} places)</p>
            </div>
            @endif

            @if($devoir->surveillants && $devoir->surveillants->count() > 0)
            <div class="bg-purple-50 p-4 rounded-md">
                <h3 class="text-sm font-medium text-purple-800 mb-1">Surveillants assignés</h3>
                <p class="text-base font-semibold">{{ $devoir->surveillants->count() }} surveillant(s)</p>
            </div>
            @endif
        </div>

        @if($devoir->surveillants && $devoir->surveillants->count() > 0)
        <div class="mt-4">
            <h3 class="text-md font-medium mb-2">Liste des surveillants</h3>
            <ul class="divide-y divide-gray-200">
                @foreach($devoir->surveillants as $surveillant)
                <li class="py-2 flex justify-between">
                    <span>{{ $surveillant->user->name }}</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        {{ $surveillant->pivot->statut === 'accepte' ? 'bg-green-100 text-green-800' : 
                        ($surveillant->pivot->statut === 'refuse' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                        {{ ucfirst($surveillant->pivot->statut) }}
                    </span>
                </li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
    @endif

    <div class="flex justify-end space-x-4">
        <a href="{{ route('enseignant.devoirs.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
            Annuler
        </a>
        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
            Mettre à jour
        </button>
    </div>
    </form>
</div>
@endsection
