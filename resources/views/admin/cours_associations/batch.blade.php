@extends('layouts.app')

@section('content')
<div class="container px-6 mx-auto grid">
    <h2 class="my-6 text-2xl font-semibold text-gray-700">
        Association par lot de cours, filières et niveaux
    </h2>

    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md">
        <form action="{{ route('admin.cours_associations.batchStore') }}" method="POST">
            @csrf

            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-700 mb-2">Sélectionner les cours</h3>
                <div class="max-h-60 overflow-y-auto p-2 border border-gray-300 rounded-md">
                    @foreach($cours as $c)
                    <div class="mb-2">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="cours_ids[]" value="{{ $c->id }}" class="form-checkbox h-5 w-5 text-purple-600">
                            <span class="ml-2 text-sm text-gray-700">{{ $c->intitule }}</span>
                        </label>
                    </div>
                    @endforeach
                </div>
                @error('cours_ids')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-700 mb-2">Sélectionner les filières</h3>
                <div class="max-h-60 overflow-y-auto p-2 border border-gray-300 rounded-md">
                    @foreach($filieres as $filiere)
                    <div class="mb-2">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="filiere_ids[]" value="{{ $filiere->id }}" class="form-checkbox h-5 w-5 text-purple-600">
                            <span class="ml-2 text-sm text-gray-700">{{ $filiere->code }} - {{ $filiere->nom }}</span>
                        </label>
                    </div>
                    @endforeach
                </div>
                @error('filiere_ids')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-700 mb-2">Sélectionner les niveaux</h3>
                <div class="max-h-60 overflow-y-auto p-2 border border-gray-300 rounded-md">
                    @foreach($niveaux as $niveau)
                    <div class="mb-2">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="niveau_ids[]" value="{{ $niveau->id }}" class="form-checkbox h-5 w-5 text-purple-600">
                            <span class="ml-2 text-sm text-gray-700">{{ $niveau->code }} - {{ $niveau->nom }}</span>
                        </label>
                    </div>
                    @endforeach
                </div>
                @error('niveau_ids')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="bg-gray-100 p-4 rounded-md mb-4">
                <h4 class="font-medium text-gray-700 mb-2">Explication</h4>
                <p class="text-sm text-gray-600">
                    Cette fonctionnalité va créer toutes les combinaisons possibles entre les cours, filières et niveaux sélectionnés.
                    Par exemple, si vous sélectionnez 2 cours, 3 filières et 2 niveaux, cela créera 12 associations (2×3×2).
                    Les associations existantes ne seront pas dupliquées.
                </p>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('admin.cours_associations.index') }}" class="px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition-colors duration-150 bg-white border border-gray-300 rounded-lg active:bg-white hover:bg-gray-100 focus:outline-none focus:shadow-outline-gray mr-2">
                    Annuler
                </a>
                <button type="submit" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    Créer les associations
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
