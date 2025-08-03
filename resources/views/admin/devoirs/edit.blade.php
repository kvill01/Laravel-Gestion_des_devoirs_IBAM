@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Modifier le devoir') }}
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
            Retour à la liste
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
            </div>
            
            <!-- Formulaire de modification -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <form action="{{ route('admin.devoirs.update', $devoir) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <h2 class="text-xl font-semibold mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Modifier la planification
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="date_heure" class="block text-sm font-medium text-gray-700 mb-1">Date et heure du devoir*</label>
                            <input type="datetime-local" name="date_heure" id="date_heure" class="form-input rounded-md shadow-sm mt-1 block w-full" 
                                value="{{ old('date_heure', $devoir->date_heure ? date('Y-m-d\TH:i', strtotime($devoir->date_heure)) : now()->format('Y-m-d\TH:i')) }}" required>
                            @error('date_heure')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="salles" class="block text-sm font-medium text-gray-700 mb-1">Salles de composition*</label>
                            <select name="salles[]" id="salles" class="form-select rounded-md shadow-sm mt-1 block w-full" multiple required>
                                @foreach(\App\Models\Salle::all() as $salle)
                                    <option value="{{ $salle->id }}" {{ $devoir->salles->contains($salle->id) ? 'selected' : '' }}>
                                        {{ $salle->nom }} (Capacité: {{ $salle->capacite }} places)
                                    </option>
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
                                @foreach(\App\Models\Surveillant::with('user')->get() as $surveillant)
                                    <div class="flex items-center">
                                        <input type="checkbox" name="surveillants[]" id="surveillant_{{ $surveillant->id }}" value="{{ $surveillant->id }}" 
                                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                            {{ $devoir->surveillants->contains($surveillant->id) ? 'checked' : '' }}>
                                        <label for="surveillant_{{ $surveillant->id }}" class="ml-2 block text-sm text-gray-900">
                                            {{ $surveillant->user->name }} {{ $surveillant->user->prenom }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @error('surveillants')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Colonne de droite -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Statut actuel -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Statut actuel
                </h2>
                
                <div class="mb-4">
                    <p class="text-gray-600 mb-1">Statut du devoir:</p>
                    <div class="flex items-center">
                        <span class="px-3 py-1 rounded-full text-sm font-medium 
                            @if($devoir->statut == 'en_attente') bg-yellow-100 text-yellow-800
                            @elseif($devoir->statut == 'confirmé') bg-green-100 text-green-800
                            @elseif($devoir->statut == 'terminé') bg-blue-100 text-blue-800
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $devoir->statut)) }}
                        </span>
                    </div>
                </div>
                
                @if($devoir->statut == 'en_attente')
                <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-100">
                    <p class="text-sm text-yellow-700">Ce devoir est en attente de confirmation. Une fois les détails modifiés, vous pourrez le confirmer depuis la page de détails.</p>
                </div>
                @endif
                
                @if($devoir->statut == 'confirmé')
                <div class="p-4 bg-green-50 rounded-lg border border-green-100">
                    <p class="text-sm text-green-700">Ce devoir est confirmé. Vos modifications seront appliquées tout en conservant son statut actuel.</p>
                </div>
                @endif
                
                @if($devoir->statut == 'terminé')
                <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
                    <p class="text-sm text-blue-700">Ce devoir est terminé. Vous pouvez encore modifier ses détails pour des raisons administratives.</p>
                </div>
                @endif
            </div>
            
            <!-- Actions rapides -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-medium text-gray-700 mb-3 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Actions rapides
                </h3>
                
                <div class="space-y-3">
                    <a href="{{ route('admin.devoirs.show', $devoir) }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-50 border border-indigo-200 rounded-md font-medium text-sm text-indigo-700 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Voir les détails
                    </a>
                    
                    @if($devoir->statut == 'en_attente')
                    <a href="{{ route('admin.devoirs.confirmer', $devoir) }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-50 border border-green-200 rounded-md font-medium text-sm text-green-700 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Confirmer le devoir
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
