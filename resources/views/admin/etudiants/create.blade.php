@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Ajouter un étudiant') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- En-tête du formulaire -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-700">Nouvel utilisateur étudiant</h3>
                        <p class="text-sm text-gray-500">Remplissez les informations ci-dessous pour créer un nouveau compte étudiant</p>
                    </div>
                    
                    <!-- Messages d'erreur de validation -->
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-md">
                            <div class="font-bold">Des erreurs sont survenues :</div>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('admin.etudiants.store') }}" class="bg-white rounded-lg shadow-md p-6">
                        @csrf

                        <!-- Informations personnelles -->
                        <div class="mb-6">
                            <h4 class="font-medium text-gray-700 mb-4">Informations personnelles</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Nom -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Nom') }}</label>
                                    <input id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('name') border-red-300 @enderror" type="text" name="name" value="{{ old('name') }}" required autofocus />
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Prénom -->
                                <div>
                                    <label for="prenom" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Prénom') }}</label>
                                    <input id="prenom" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('prenom') border-red-300 @enderror" type="text" name="prenom" value="{{ old('prenom') }}" required />
                                    @error('prenom')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Email -->
                            <div class="mt-4">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Email') }}</label>
                                <input id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('email') border-red-300 @enderror" type="email" name="email" value="{{ old('email') }}" required />
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Date de naissance -->
                            <div class="mt-4">
                                <label for="date_naissance" class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ __('Date de naissance') }}
                                </label>
                                <input id="date_naissance" name="date_naissance" type="date" value="{{ old('date_naissance') }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('date_naissance') border-red-300 @enderror">
                                @error('date_naissance')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-6 border-gray-200">

                        <!-- Informations académiques -->
                        <div class="mb-6">
                            <h4 class="font-medium text-gray-700 mb-4">Informations académiques</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Filière -->
                                <div>
                                    <label for="filiere_id" class="block text-sm font-medium text-gray-700 mb-1">
                                        {{ __('Filière') }}
                                    </label>
                                    <select id="filiere_id" name="filiere_id" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('filiere_id') border-red-300 @enderror">
                                        <option value="">Sélectionnez une filière</option>
                                        @foreach($filieres as $filiere)
                                            <option value="{{ $filiere->id }}" {{ old('filiere_id') == $filiere->id ? 'selected' : '' }}>
                                                {{ $filiere->code }} - {{ $filiere->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('filiere_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Niveau -->
                                <div>
                                    <label for="niveau_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Niveau') }}</label>
                                    <select id="niveau_id" name="niveau_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('niveau_id') border-red-300 @enderror" required>
                                        <option value="">Sélectionner un niveau</option>
                                        @foreach($niveaux as $niveau)
                                            <option value="{{ $niveau->id }}" {{ old('niveau_id') == $niveau->id ? 'selected' : '' }}>
                                                {{ $niveau->code }} - {{ $niveau->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('niveau_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Année académique -->
                                <div>
                                    <label for="annee_academique_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Année académique') }}</label>
                                    <select id="annee_academique_id" name="annee_academique_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('annee_academique_id') border-red-300 @enderror" required>
                                        <option value="">Sélectionner une année académique</option>
                                        @foreach($anneeAcademiques as $annee)
                                            <option value="{{ $annee->id }}" {{ old('annee_academique_id') == $annee->id ? 'selected' : '' }}>
                                                {{ $annee->annee }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('annee_academique_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr class="my-6 border-gray-200">

                        <!-- Informations de connexion -->
                        <div class="mb-6">
                            <h4 class="font-medium text-gray-700 mb-4">Informations de connexion</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Mot de passe -->
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Mot de passe') }}</label>
                                    <input id="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('password') border-red-300 @enderror" type="password" name="password" required />
                                    @error('password')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Confirmation du mot de passe -->
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Confirmer le mot de passe') }}</label>
                                    <input id="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="password" name="password_confirmation" required />
                                    @error('password_confirmation')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr class="my-6 border-gray-200">

                        <div class="flex items-center justify-between">
                            <a href="{{ route('admin.etudiants.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Retour
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Créer l'étudiant
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection