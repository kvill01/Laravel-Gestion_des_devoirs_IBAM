@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Créer un nouveau devoir') }}
    </h2>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800"></h1>
        <a href="{{ route('enseignant.devoirs.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
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

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('enseignant.devoirs.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Informations générales -->
            <div class="mb-6">
                <h2 class="text-lg font-medium text-gray-800 mb-4 pb-2 border-b">1. Informations générales</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nom_devoir" class="block text-sm font-medium text-gray-700 mb-1">Nom du devoir*</label>
                        <input type="text" name="nom_devoir" id="nom_devoir" class="form-input rounded-md shadow-sm mt-1 block w-full" value="{{ old('nom_devoir') }}" required>
                        <small class="text-gray-500">Exemple: Contrôle continu 1, Examen final, etc.</small>
                    </div>

                    <div>
                        <label for="cours_id" class="block text-sm font-medium text-gray-700 mb-1">Cours*</label>
                        <select name="cours_id" id="cours_id" class="form-select rounded-md shadow-sm mt-1 block w-full" required>
                            <option value="">Sélectionner un cours</option>
                            @forelse($cours as $c)
                            <option value="{{ $c->id }}" {{ old('cours_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->intitule }}
                                @if($c->enseignants_id == Auth::user()->enseignant->id)
                                    (Responsable)
                                @endif
                            </option>
                            @empty
                            <option value="" disabled>Aucun cours disponible</option>
                            @endforelse
                        </select>
                        @if($cours->isEmpty())
                        <p class="mt-2 text-sm text-red-600">
                            Vous n'avez pas encore de cours assignés. Veuillez contacter l'administration.
                        </p>
                        @endif
                        <small class="text-gray-500">Le cours auquel ce devoir est associé</small>
                    </div>
                </div>
            </div>

            <!-- Formation et niveau -->
            <div class="mb-6">
                <h2 class="text-lg font-medium text-gray-800 mb-4 pb-2 border-b">2. Formation et niveau</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="filiere_id" class="block text-sm font-medium text-gray-700 mb-1">Filière*</label>
                        <select name="filiere_id" id="filiere_id" class="form-select rounded-md shadow-sm mt-1 block w-full" required>
                            <option value="">Sélectionner une filière</option>
                            @foreach($filieres as $filiere)
                            <option value="{{ $filiere->id }}" {{ old('filiere_id') == $filiere->id ? 'selected' : '' }}>{{ $filiere->nom }} ({{ $filiere->code }})</option>
                            @endforeach
                        </select>
                        <small class="text-gray-500">La filière concernée par ce devoir</small>
                    </div>

                    <div>
                        <label for="niveau_id" class="block text-sm font-medium text-gray-700 mb-1">Niveau*</label>
                        <select name="niveau_id" id="niveau_id" class="form-select rounded-md shadow-sm mt-1 block w-full" required>
                            <option value="">Sélectionner un niveau</option>
                            @foreach($niveaux as $niveau)
                            <option value="{{ $niveau->id }}" {{ old('niveau_id') == $niveau->id ? 'selected' : '' }}>{{ $niveau->nom }} ({{ $niveau->code }})</option>
                            @endforeach
                        </select>
                        <small class="text-gray-500">Le niveau concerné par ce devoir</small>
                    </div>

                    <div>
                        <label for="annee_academique_id" class="block text-sm font-medium text-gray-700 mb-1">Année académique*</label>
                        <select name="annee_academique_id" id="annee_academique_id" class="form-select rounded-md shadow-sm mt-1 block w-full" required>
                            <option value="">Sélectionner une année</option>
                            @foreach($annees as $annee)
                            <option value="{{ $annee->id }}" {{ old('annee_academique_id') == $annee->id ? 'selected' : '' }}>{{ $annee->annee_debut }}-{{ $annee->annee_fin }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Planification -->
            <div class="mb-6">
                <h2 class="text-lg font-medium text-gray-800 mb-4 pb-2 border-b">3. Planification</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="semestre_id" class="block text-sm font-medium text-gray-700 mb-1">Semestre (optionnel)</label>
                        <select name="semestre_id" id="semestre_id" class="form-select rounded-md shadow-sm mt-1 block w-full">
                            <option value="">Sélectionner un semestre</option>
                            @foreach($semestres as $semestre)
                            <option value="{{ $semestre->id }}" {{ old('semestre_id') == $semestre->id ? 'selected' : '' }}>{{ $semestre->libelle }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="duree_minutes" class="block text-sm font-medium text-gray-700 mb-1">Durée (en minutes)*</label>
                        <input type="number" name="duree_minutes" id="duree_minutes" class="form-input rounded-md shadow-sm mt-1 block w-full" value="{{ old('duree_minutes', 60) }}" min="15" max="240" required>
                        <small class="text-gray-500">Durée maximale: 240 minutes (4 heures)</small>
                    </div>

                    <div>
                        <label for="date_proposee" class="block text-sm font-medium text-gray-700 mb-1">Date proposée</label>
                        <input type="date" name="date_proposee" id="date_proposee" class="form-input rounded-md shadow-sm mt-1 block w-full" value="{{ old('date_proposee') }}" min="{{ date('Y-m-d') }}">
                        <small class="text-gray-500">Proposition de date pour l'administration</small>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div>
                        <label for="heure_proposee" class="block text-sm font-medium text-gray-700 mb-1">Heure proposée</label>
                        <input type="time" name="heure_proposee" id="heure_proposee" class="form-input rounded-md shadow-sm mt-1 block w-full" value="{{ old('heure_proposee') }}">
                        <small class="text-gray-500">Proposition d'heure pour l'administration</small>
                    </div>
                </div>
            </div>

            <!-- Salles et surveillants (à titre informatif) -->
            <div class="mb-6 bg-blue-50 p-4 rounded-md">
                <h2 class="text-lg font-medium text-gray-800 mb-2">4. Information sur l'attribution de salles et surveillants</h2>
                
                <div class="text-sm text-gray-700 mb-4">
                    <p>Après la création de votre devoir, l'administration sera chargée de :</p>
                    <ul class="list-disc ml-6 my-2">
                        <li>Confirmer ou modifier la date et l'heure proposées</li>
                        <li>Attribuer une ou plusieurs salles pour le déroulement de l'examen</li>
                        <li>Désigner les surveillants responsables de la supervision</li>
                    </ul>
                    <p>Vous pourrez consulter ces informations une fois le devoir confirmé par l'administration.</p>
                </div>
            </div>

            <!-- Documents et commentaires -->
            <div class="mb-6">
                <h2 class="text-lg font-medium text-gray-800 mb-4 pb-2 border-b">5. Documents et commentaires</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="fichier_sujet" class="block text-sm font-medium text-gray-700 mb-1">Fichier du sujet (PDF, DOC, DOCX)*</label>
                        <input type="file" name="fichier_sujet" id="fichier_sujet" class="form-input rounded-md shadow-sm mt-1 block w-full" required accept=".pdf,.doc,.docx">
                        <small class="text-gray-500">Taille maximale: 5 Mo</small>
                    </div>

                    <div>
                        <label for="commentaire" class="block text-sm font-medium text-gray-700 mb-1">Commentaires pour l'administration</label>
                        <textarea name="commentaire" id="commentaire" rows="3" class="form-textarea rounded-md shadow-sm mt-1 block w-full">{{ old('commentaire') }}</textarea>
                        <small class="text-gray-500">Précisions ou demandes particulières pour l'administration</small>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 p-4 rounded-md mb-6">
                <h3 class="text-md font-medium text-blue-700 mb-2">Note importante</h3>
                <p class="text-sm text-gray-600 mb-2">
                    Ce devoir sera soumis à l'administration pour confirmation. L'administrateur attribuera une date, une heure, une salle et des surveillants définitifs.
                </p>
                <p class="text-sm text-gray-600">
                    Vous pouvez proposer une date et une heure qui vous conviendraient, mais la décision finale appartient à l'administration.
                </p>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Créer le devoir
                </button>
            </div>
        </form>
    </div>
</div>
@endsection