<!-- resources/views/etudiant/dashboard.blade.php -->
@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Tableau de bord Étudiant') }}
    </h2>
@endsection

@section('content')
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Résumé des statistiques en haut -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Devoirs à venir -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl shadow-lg overflow-hidden border-l-4 border-blue-500 transform transition-all duration-300 hover:scale-105 hover:shadow-xl">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-gradient-to-r from-blue-400 to-blue-600 mr-5 shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-blue-600 uppercase tracking-wider">Devoirs à venir</p>
                                <div class="flex items-baseline">
                                    <p class="text-3xl font-bold text-gray-800">{{ $statistiques['devoirs_a_venir'] }}</p>
                                    <p class="ml-2 text-sm text-gray-600">tâches</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Devoirs terminés -->
                <div class="bg-gradient-to-br from-green-50 to-emerald-100 rounded-xl shadow-lg overflow-hidden border-l-4 border-green-500 transform transition-all duration-300 hover:scale-105 hover:shadow-xl">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-gradient-to-r from-green-400 to-green-600 mr-5 shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-green-600 uppercase tracking-wider">Devoirs terminés</p>
                                <div class="flex items-baseline">
                                    <p class="text-3xl font-bold text-gray-800">{{ $statistiques['devoirs_passes'] }}</p>
                                    <p class="ml-2 text-sm text-gray-600">tâches</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Total des devoirs -->
                <div class="bg-gradient-to-br from-purple-50 to-violet-100 rounded-xl shadow-lg overflow-hidden border-l-4 border-purple-500 transform transition-all duration-300 hover:scale-105 hover:shadow-xl">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-gradient-to-r from-purple-400 to-purple-600 mr-5 shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-purple-600 uppercase tracking-wider">Total des devoirs</p>
                                <div class="flex items-baseline">
                                    <p class="text-3xl font-bold text-gray-800">{{ $statistiques['total_devoirs'] }}</p>
                                    <p class="ml-2 text-sm text-gray-600">tâches</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Informations de l'étudiant avec design amélioré -->
                <div class="bg-white overflow-hidden shadow-md rounded-lg">
                    <div class="bg-gray-50 px-6 py-4 border-b">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-700">Mes informations</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-center border-b pb-3">
                                <span class="text-gray-600 w-1/3">Nom:</span>
                                <span class="font-medium text-gray-800">{{ Auth::user()->name }}</span>
                            </div>
                            <div class="flex items-center border-b pb-3">
                                <span class="text-gray-600 w-1/3">Prénom:</span>
                                <span class="font-medium text-gray-800">{{ Auth::user()->prenom }}</span>
                            </div>
                            <div class="flex items-center border-b pb-3">
                                <span class="text-gray-600 w-1/3">Email:</span>
                                <span class="font-medium text-gray-800">{{ Auth::user()->email }}</span>
                            </div>
                            <div class="flex items-center border-b pb-3">
                                <span class="text-gray-600 w-1/3">Filière:</span>
                                <span class="font-medium text-gray-800">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                        {{ Auth::user()->etudiant->filiere->code ?? 'Non définie' }}
                                    </span>
                                </span>
                            </div>
                            <div class="flex items-center border-b pb-3">
                                <span class="text-gray-600 w-1/3">Niveau:</span>
                                <span class="font-medium text-gray-800">
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                                        {{ Auth::user()->etudiant->niveau->code ?? 'Non défini' }}
                                    </span>
                                </span>
                            </div>
                            <div class="flex items-center">
                                <span class="text-gray-600 w-1/3">Année:</span>
                                <span class="font-medium text-gray-800">
                                    <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded-full">
                                        {{ Auth::user()->etudiant->anneeAcademique ? Auth::user()->etudiant->anneeAcademique->annee_debut . '-' . Auth::user()->etudiant->anneeAcademique->annee_fin : 'Non définie' }}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="bg-white overflow-hidden shadow-md rounded-lg">
                    <div class="bg-gray-50 px-6 py-4 border-b">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-700">Progression académique</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="mb-4">
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">Devoirs complétés</span>
                                <span class="text-sm font-medium text-gray-700">{{ $statistiques['devoirs_passes'] }}/{{ $statistiques['total_devoirs'] }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                @php
                                    $progressPercentage = $statistiques['total_devoirs'] > 0 ? ($statistiques['devoirs_passes'] / $statistiques['total_devoirs']) * 100 : 0;
                                @endphp
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $progressPercentage }}%"></div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 mt-6">
                            <div class="bg-gray-50 p-4 rounded-lg text-center">
                                <span class="block text-2xl font-bold text-blue-600">{{ $statistiques['devoirs_a_venir'] }}</span>
                                <span class="text-sm text-gray-500">À venir</span>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg text-center">
                                <span class="block text-2xl font-bold text-green-600">{{ $statistiques['devoirs_passes'] }}</span>
                                <span class="text-sm text-gray-500">Terminés</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Accès rapides avec icônes -->
                <div class="bg-white overflow-hidden shadow-md rounded-lg">
                    <div class="bg-gray-50 px-6 py-4 border-b">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-700">Accès rapides</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-4">
                            <a href="{{ route('etudiant.devoirs.index') }}" class="flex flex-col items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <span class="text-sm font-medium text-gray-700">Mes devoirs</span>
                            </a>
                            <a href="{{ route('etudiant.emploi-du-temps') }}" class="flex flex-col items-center p-4 bg-yellow-50 hover:bg-yellow-100 rounded-lg transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-700">Emploi du temps</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Prochains devoirs avec design amélioré -->
            <div class="bg-white overflow-hidden shadow-md rounded-lg mt-6">
                <div class="bg-gray-50 px-6 py-4 border-b">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-700">Mes prochains devoirs</h3>
                        </div>
                        <a href="{{ route('etudiant.devoirs.index') }}" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition ease-in-out duration-150">
                            <span>Voir tous</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    @if(isset($devoirs) && count($devoirs) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Devoir</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cours</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Salles</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($devoirs as $devoir)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $devoir->nom_devoir }}</div>
                                                <div class="text-xs text-gray-500">{{ $devoir->enseignant->nom }} {{ $devoir->enseignant->prenom }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    {{ $devoir->cours->intitule }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ \Carbon\Carbon::parse($devoir->date_heure)->locale('fr')->isoFormat('dddd') }}
                                                    {{ \Carbon\Carbon::parse($devoir->date_heure)->format('d/m/Y') }}
                                                </div>
                                                <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($devoir->date_heure)->format('H:i') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach($devoir->salles as $salle)
                                                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">{{ $salle->nom }}</span>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('etudiant.devoirs.show', $devoir) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-5 font-medium rounded-md text-indigo-600 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:border-indigo-300 focus:shadow-outline-indigo active:bg-indigo-200 transition ease-in-out duration-150">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    Détails
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="bg-gray-50 rounded-lg p-6 text-center">
                            <p class="text-gray-600 mb-2">
                                Vous n'avez pas de devoirs à venir pour le moment.
                            </p>
                            <p class="text-sm text-gray-500">
                                Filière: {{ Auth::user()->etudiant->filiere->code ?? 'Non définie' }} | 
                                Niveau: {{ Auth::user()->etudiant->niveau->code ?? 'Non défini' }} | 
                                Année: {{ Auth::user()->etudiant->anneeAcademique ? Auth::user()->etudiant->anneeAcademique->annee_debut . '-' . Auth::user()->etudiant->anneeAcademique->annee_fin : 'Non définie' }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection