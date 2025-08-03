@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Modifier un enseignant') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.enseignants.update', $enseignant) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Nom -->
                        <div class="mb-4">
                            <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
                            <input type="text" name="nom" id="nom" value="{{ $enseignant->nom }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <!-- Prenom -->
                        <div class="mb-4">
                            <label for="prenom" class="block text-sm font-medium text-gray-700">Prénom</label>
                            <input type="text" name="prenom" id="prenom" value="{{ $enseignant->prenom }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ $enseignant->email }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <!-- Domaine -->
                        <div class="mb-4">
                            <label for="domaine_id" class="block text-sm font-medium text-gray-700">Domaine</label>
                            <select id="domaine_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" name="domaine_id" required>
                                <option value="">Sélectionnez un domaine</option>
                                @foreach($domaines as $domaine)
                                    <option value="{{ $domaine->id }}" {{ $enseignant->domaine_id == $domaine->id ? 'selected' : '' }}>
                                        {{ $domaine->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('domaine_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Grade -->
                        <div class="mb-4">
                            <label for="grade" class="block text-sm font-medium text-gray-700">Grade</label>
                            <select id="grade" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" name="grade" required>
                                <option value="">Sélectionnez un grade</option>
                                <option value="Professeur Titulaire" {{ $enseignant->grade == 'Professeur Titulaire' ? 'selected' : '' }}>Professeur Titulaire</option>
                                <option value="Maître de Conférences" {{ $enseignant->grade == 'Maître de Conférences' ? 'selected' : '' }}>Maître de Conférences</option>
                                <option value="Maître-Assistant" {{ $enseignant->grade == 'Maître-Assistant' ? 'selected' : '' }}>Maître-Assistant</option>
                                <option value="Assistant" {{ $enseignant->grade == 'Assistant' ? 'selected' : '' }}>Assistant</option>
                                <option value="Chargé de Cours" {{ $enseignant->grade == 'Chargé de Cours' ? 'selected' : '' }}>Chargé de Cours</option>
                                <option value="Professeur Émérite" {{ $enseignant->grade == 'Professeur Émérite' ? 'selected' : '' }}>Professeur Émérite</option>
                            </select>
                            @error('grade')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Cours -->
                        <div class="mb-4">
                            <label for="cours" class="block text-sm font-medium text-gray-700">Cours associés</label>
                            <select id="cours" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                                    name="cours[]" multiple>
                                @foreach($cours as $c)
                                    <option value="{{ $c->id }}" {{ in_array($c->id, $coursAssignes) ? 'selected' : '' }}>
                                        {{ $c->intitule }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-sm text-gray-500">Maintenez la touche Ctrl (ou Cmd sur Mac) pour sélectionner plusieurs cours</p>
                            @error('cours')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex justify-end">
                            <a href="{{ route('admin.enseignants.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                                Annuler
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300">
                                Modifier
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection