@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Ajouter une salle') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="mb-4">
                    <a href="{{ route('admin.salles.index') }}" class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
                    </a>
                </div>

                <h3 class="text-lg font-semibold mb-6">Ajouter une nouvelle salle</h3>

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Oups!</strong>
                        <span class="block sm:inline">Veuillez corriger les erreurs suivantes :</span>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.salles.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="mb-4">
                            <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">Nom de la salle*</label>
                            <input type="text" name="nom" id="nom" value="{{ old('nom') }}" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                        </div>

                        <div class="mb-4">
                            <label for="capacite" class="block text-sm font-medium text-gray-700 mb-2">Capacité (nombre de places)*</label>
                            <input type="number" name="capacite" id="capacite" value="{{ old('capacite') }}" min="1" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                        </div>

                        <div class="mb-4">
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type de salle*</label>
                            <select name="type" id="type" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                                <option value="">Sélectionner un type</option>
                                <option value="Salle de cours" {{ old('type') == 'Salle de cours' ? 'selected' : '' }}>Salle de cours</option>
                                <option value="Amphithéâtre" {{ old('type') == 'Amphithéâtre' ? 'selected' : '' }}>Amphithéâtre</option>
                                <option value="Laboratoire" {{ old('type') == 'Laboratoire' ? 'selected' : '' }}>Laboratoire</option>
                                <option value="Salle informatique" {{ old('type') == 'Salle informatique' ? 'selected' : '' }}>Salle informatique</option>
                                <option value="Salle de réunion" {{ old('type') == 'Salle de réunion' ? 'selected' : '' }}>Salle de réunion</option>
                                <option value="Autre" {{ old('type') == 'Autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="localisation" class="block text-sm font-medium text-gray-700 mb-2">Localisation*</label>
                            <input type="text" name="localisation" id="localisation" value="{{ old('localisation') }}" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                            <p class="text-xs text-gray-500 mt-1">Ex: Bâtiment A, 2ème étage</p>
                        </div>

                        <div class="mb-4 col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" id="description" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">{{ old('description') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="disponible" id="disponible" value="1" {{ old('disponible') ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="disponible" class="ml-2 block text-sm text-gray-700">Disponible</label>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Cochez si la salle est actuellement disponible pour les devoirs</p>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end">
                        <button type="button" onclick="window.location='{{ route('admin.salles.index') }}'" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                            Annuler
                        </button>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
