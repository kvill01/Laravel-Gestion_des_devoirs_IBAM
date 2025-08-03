@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="max-w-lg w-full">
        <!-- Card principale -->
        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            <!-- Header avec logo -->
            <div class="p-6 sm:p-10 bg-white border-b">
                <div class="flex justify-center mb-6">
                    <img src="{{ asset('images/logo_ibam.jpg') }}" alt="Logo" class="h-20">
                </div>
                <h1 class="text-center text-2xl font-bold text-gray-900">Inscription</h1>
            </div>

            <!-- Formulaire -->
            <div class="p-6 sm:p-10 pt-8">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Informations personnelles -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Nom -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nom
                            </label>
                            <input id="name" name="name" type="text" value="{{ old('name') }}" required
                                   class="w-full px-4 py-3 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('name') border-red-500 @enderror"
                                   placeholder="Votre nom">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Prénom -->
                        <div>
                            <label for="prenom" class="block text-sm font-medium text-gray-700 mb-2">
                                Prénom
                            </label>
                            <input id="prenom" name="prenom" type="text" value="{{ old('prenom') }}" required
                                   class="w-full px-4 py-3 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('prenom') border-red-500 @enderror"
                                   placeholder="Votre prénom">
                            @error('prenom')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Date de naissance -->
                    <div class="mb-6">
                        <label for="date_naissance" class="block text-sm font-medium text-gray-700 mb-2">
                            Date de naissance
                        </label>
                        <input id="date_naissance" name="date_naissance" type="date" value="{{ old('date_naissance') }}" required
                               class="w-full px-4 py-3 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('date_naissance') border-red-500 @enderror">
                        @error('date_naissance')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Informations académiques -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Filière -->
                        <div>
                            <label for="filiere_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Filière
                            </label>
                            <select id="filiere_id" name="filiere_id" required
                                    class="w-full px-4 py-3 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('filiere_id') border-red-500 @enderror">
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
                            <label for="niveau_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Niveau
                            </label>
                            <select id="niveau_id" name="niveau_id" required
                                    class="w-full px-4 py-3 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('niveau_id') border-red-500 @enderror">
                                <option value="">Sélectionnez un niveau</option>
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
                    </div>

                    <!-- Année académique -->
                    <div class="mb-6">
                        <label for="annee_academique_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Année Académique
                        </label>
                        <select id="annee_academique_id" name="annee_academique_id" required
                                class="w-full px-4 py-3 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('annee_academique_id') border-red-500 @enderror">
                            @foreach($annees_academiques as $annee)
                                <option value="{{ $annee->id }}" 
                                    {{ old('annee_academique_id', $annee_academique_actuelle->id ?? '') == $annee->id ? 'selected' : '' }}>
                                    {{ $annee->annee }}
                                </option>
                            @endforeach
                        </select>
                        @error('annee_academique_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Adresse Email
                        </label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required
                               class="w-full px-4 py-3 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('email') border-red-500 @enderror"
                               placeholder="votre@email.com">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mot de passe -->
                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Mot de passe
                        </label>
                        <input id="password" name="password" type="password" required
                               class="w-full px-4 py-3 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('password') border-red-500 @enderror"
                               placeholder="••••••••">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirmation du mot de passe -->
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirmer le mot de passe
                        </label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                               class="w-full px-4 py-3 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                               placeholder="••••••••">
                    </div>

                    <!-- Bouton d'inscription -->
                    <div class="mb-0">
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            S'inscrire
                        </button>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="px-6 sm:px-10 py-4 bg-gray-50 border-t border-gray-200 text-center">
                <p class="text-sm text-gray-600">
                    Vous avez déjà un compte?
                    <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">
                        Se connecter
                    </a>
                </p>
            </div>
        </div>
        
        <!-- Copyright -->
        <div class="mt-6 text-center">
            <p class="text-xs text-gray-500"> {{ date('Y') }} IBAM. Tous droits réservés.</p>
        </div>
    </div>
</div>
@endsection