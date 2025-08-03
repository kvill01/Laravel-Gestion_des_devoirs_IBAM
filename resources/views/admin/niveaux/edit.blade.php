@extends('layouts.app')

@section('content')
<div class="container px-6 mx-auto grid">
    <h2 class="my-6 text-2xl font-semibold text-gray-700">
        Modifier le niveau: {{ $niveau->nom }}
    </h2>

    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md">
        <form action="{{ route('admin.niveaux.update', $niveau) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700" for="code">
                    Code
                </label>
                <input
                    class="block w-full mt-1 text-sm border-gray-300 rounded-md focus:border-purple-400 focus:outline-none focus:shadow-outline-purple form-input @error('code') border-red-500 @enderror"
                    type="text"
                    id="code"
                    name="code"
                    value="{{ old('code', $niveau->code) }}"
                    placeholder="Ex: L1, L2, M1, etc."
                    required
                />
                @error('code')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Le code doit être unique et sera affiché en majuscules.</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700" for="nom">
                    Nom
                </label>
                <input
                    class="block w-full mt-1 text-sm border-gray-300 rounded-md focus:border-purple-400 focus:outline-none focus:shadow-outline-purple form-input @error('nom') border-red-500 @enderror"
                    type="text"
                    id="nom"
                    name="nom"
                    value="{{ old('nom', $niveau->nom) }}"
                    placeholder="Ex: Licence 1, Master 2"
                    required
                />
                @error('nom')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700" for="description">
                    Description
                </label>
                <textarea
                    class="block w-full mt-1 text-sm border-gray-300 rounded-md focus:border-purple-400 focus:outline-none focus:shadow-outline-purple form-textarea @error('description') border-red-500 @enderror"
                    id="description"
                    name="description"
                    rows="3"
                    placeholder="Description du niveau"
                >{{ old('description', $niveau->description) }}</textarea>
                @error('description')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <a href="{{ route('admin.niveaux.index') }}" class="px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition-colors duration-150 bg-white border border-gray-300 rounded-lg active:bg-white hover:bg-gray-100 focus:outline-none focus:shadow-outline-gray mr-2">
                    Annuler
                </a>
                <button type="submit" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
