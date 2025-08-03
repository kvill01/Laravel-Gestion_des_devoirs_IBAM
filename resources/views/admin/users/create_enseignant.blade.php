@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Ajouter un enseignant') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <form method="POST" action="{{ route('admin.enseignants.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
                        <input id="nom" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" name="nom" value="{{ old('nom') }}" required autofocus>
                        @error('nom')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="prenom" class="block text-sm font-medium text-gray-700">Prénom</label>
                        <input id="prenom" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" name="prenom" value="{{ old('prenom') }}" required>
                        @error('prenom')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input id="email" type="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="domaine_id" class="block text-sm font-medium text-gray-700">Domaine</label>
                        <select id="domaine_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" name="domaine_id" required>
                            <option value="">Sélectionnez un domaine</option>
                            @foreach($domaines as $domaine)
                                <option value="{{ $domaine->id }}" {{ old('domaine_id') == $domaine->id ? 'selected' : '' }}>
                                    {{ $domaine->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('domaine_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="grade" class="block text-sm font-medium text-gray-700">Grade</label>
                        <select id="grade" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" name="grade" required>
                            <option value="">Sélectionnez un grade</option>
                            <option value="Professeur Titulaire" {{ old('grade') == 'Professeur Titulaire' ? 'selected' : '' }}>Professeur Titulaire</option>
                            <option value="Maître de Conférences" {{ old('grade') == 'Maître de Conférences' ? 'selected' : '' }}>Maître de Conférences</option>
                            <option value="Maître-Assistant" {{ old('grade') == 'Maître-Assistant' ? 'selected' : '' }}>Maître-Assistant</option>
                            <option value="Assistant" {{ old('grade') == 'Assistant' ? 'selected' : '' }}>Assistant</option>
                            <option value="Chargé de Cours" {{ old('grade') == 'Chargé de Cours' ? 'selected' : '' }}>Chargé de Cours</option>
                            <option value="Professeur Émérite" {{ old('grade') == 'Professeur Émérite' ? 'selected' : '' }}>Professeur Émérite</option>
                        </select>
                        @error('grade')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                        <input id="password" type="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" name="password" required>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="password-confirm" class="block text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
                        <input id="password-confirm" type="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" name="password_confirmation" required>
                    </div>

                    <div class="flex items-center justify-end">
                        <a href="{{ route('admin.enseignants.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                            Annuler
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Ajouter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection