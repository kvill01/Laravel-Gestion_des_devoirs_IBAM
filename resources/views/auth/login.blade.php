@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="max-w-md w-full">
        <!-- Card principale -->
        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            <!-- Header avec logo -->
            <div class="p-6 sm:p-10 bg-white border-b">
                <div class="flex justify-center mb-6">
                    <img src="{{ asset('images/logo_ibam.jpg') }}" alt="Logo" class="h-20">
                </div>
                <h1 class="text-center text-2xl font-bold text-gray-900">Connexion</h1>
                
                <!-- Message de bienvenue -->
                <div class="mt-4 p-4 bg-blue-50 border border-blue-100 rounded-lg text-center">
                    <h2 class="text-lg font-semibold text-blue-800 mb-1">Bienvenue sur IBAM Devoirs</h2>
                    <p class="text-sm text-blue-700">Logiciel de gestion des devoirs et examens de l'Institut Burkinabé des Arts et Métiers</p>
                </div>
            </div>

            <!-- Formulaire -->
            <div class="p-6 sm:p-10 pt-8">
                @if(session('status'))
                    <div class="mb-6 bg-green-100 p-4 rounded-md text-green-800 text-sm">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                               class="w-full px-4 py-3 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('email') border-red-500 @enderror"
                               placeholder="votre@email.com">
                        
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mot de passe -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block text-sm font-medium text-gray-700">
                                Mot de passe
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:underline">
                                    Mot de passe oublié?
                                </a>
                            @endif
                        </div>
                        <input id="password" type="password" name="password" required
                               class="w-full px-4 py-3 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('password') border-red-500 @enderror"
                               placeholder="••••••••">
                        
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Se souvenir de moi -->
                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Se souvenir de moi</span>
                        </label>
                    </div>

                    <!-- Bouton de connexion -->
                    <div class="mb-0">
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Se connecter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            @if(Route::has('register'))
                <div class="px-6 sm:px-10 py-4 bg-gray-50 border-t border-gray-200 text-center">
                    <p class="text-sm text-gray-600">
                        Vous n'avez pas de compte?
                        <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500">
                            S'inscrire
                        </a>
                    </p>
                </div>
            @endif
        </div>
        
        <!-- Copyright -->
        <div class="mt-6 text-center">
            <p class="text-xs text-gray-500"> IBAM - Tous droits réservés</p>
        </div>
    </div>
</div>
@endsection