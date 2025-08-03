<!-- resources/views/enseignant/dashboard.blade.php -->
@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Tableau de bord Enseignant') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Section supérieure avec statistiques et info personnelles -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Card Profil -->
                <div class="bg-white overflow-hidden shadow-xl rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center mb-6">
                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-3 rounded-full mr-4 shadow">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800">Mon Profil</h3>
                        </div>
                        <div class="space-y-3">
                            <p class="flex justify-between border-b border-gray-100 pb-2"><span class="text-gray-500">Nom:</span> <span class="font-medium">{{ Auth::user()->name }}</span></p>
                            <p class="flex justify-between border-b border-gray-100 pb-2"><span class="text-gray-500">Prénom:</span> <span class="font-medium">{{ Auth::user()->prenom }}</span></p>
                            <p class="flex justify-between border-b border-gray-100 pb-2"><span class="text-gray-500">Email:</span> <span class="font-medium text-blue-600">{{ Auth::user()->email }}</span></p>
                            <p class="flex justify-between border-b border-gray-100 pb-2"><span class="text-gray-500">Grade:</span> <span class="font-medium">{{ Auth::user()->enseignant->grade ?? 'Non spécifié' }}</span></p>
                            <p class="flex justify-between border-b border-gray-100 pb-2"><span class="text-gray-500">Domaine:</span> <span class="font-medium">{{ Auth::user()->enseignant->domaine->nom ?? 'Non spécifié' }}</span></p>
                        </div>
                        <div class="mt-6">
                            <a href="#" class="inline-flex items-center px-4 py-2 bg-blue-50 border border-blue-100 rounded-md text-sm font-medium text-blue-700 hover:bg-blue-100 transition duration-150 ease-in-out">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                                Modifier mon profil
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Card Statistiques -->
                <div class="bg-white overflow-hidden shadow-xl rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center mb-6">
                            <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-3 rounded-full mr-4 shadow">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800">Mes Statistiques</h3>
                        </div>
                        @php
                            $coursResponsable = App\Models\Cours::where('enseignants_id', Auth::user()->enseignant->id)->get();
                            $coursAssocies = Auth::user()->enseignant->cours;
                            $allCours = $coursResponsable->merge($coursAssocies)->unique('id');
                            $filieres = $allCours->pluck('filieres')->flatten()->unique('id');
                            $niveaux = $allCours->pluck('niveaux')->flatten()->unique('id');
                        @endphp
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-blue-50 p-4 rounded-lg text-center shadow-sm border border-blue-100">
                                <p class="text-3xl font-bold text-blue-600">{{ $allCours->count() }}</p>
                                <p class="text-sm text-gray-600 mt-1">Cours</p>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg text-center shadow-sm border border-green-100">
                                <p class="text-3xl font-bold text-green-600">{{ count($devoirs) }}</p>
                                <p class="text-sm text-gray-600 mt-1">Devoirs</p>
                            </div>
                            <div class="bg-purple-50 p-4 rounded-lg text-center shadow-sm border border-purple-100">
                                <p class="text-3xl font-bold text-purple-600">{{ $filieres->count() }}</p>
                                <p class="text-sm text-gray-600 mt-1">Filières</p>
                            </div>
                            <div class="bg-yellow-50 p-4 rounded-lg text-center shadow-sm border border-yellow-100">
                                <p class="text-3xl font-bold text-yellow-600">{{ $niveaux->count() }}</p>
                                <p class="text-sm text-gray-600 mt-1">Niveaux</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Actions Rapides -->
                <div class="bg-white overflow-hidden shadow-xl rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center mb-6">
                            <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-3 rounded-full mr-4 shadow">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800">Actions Rapides</h3>
                        </div>
                        <!-- Actions rapides avec styles HTML basiques -->
                        <div class="space-y-4">
                            <a href="{{ route('enseignant.devoirs.create') }}" class="block text-center py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition duration-150 ease-in-out shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Créer un devoir
                            </a>
                            
                            <a href="{{ route('enseignant.cours.index') }}" class="block text-center py-3 px-4 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition duration-150 ease-in-out shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                Voir mes cours
                            </a>
                            
                            <a href="{{ route('enseignant.devoirs.index') }}" class="block text-center py-3 px-4 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-semibold transition duration-150 ease-in-out shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H3.375a2 2 0 00-1.5 1.5l.069 12A2 2 0 004.44 20h15.111a2 2 0 001.5-1.5l-.07-12A2 2 0 0018.625 5H13.5a2 2 0 00-2 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2" />
                                </svg>
                                Tous mes devoirs
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section avec les statistiques des devoirs -->
            <div class="bg-white overflow-hidden shadow-xl rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center mb-6">
                        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white p-3 rounded-full mr-4 shadow">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Statut de mes devoirs</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-yellow-50 p-4 rounded-lg text-center shadow-sm border border-yellow-100">
                            <p class="text-3xl font-bold text-yellow-600">{{ $devoirs->where('statut', 'en_attente')->count() }}</p>
                            <p class="text-sm text-gray-600 mt-1">En attente</p>
                        </div>
                        <div class="bg-blue-50 p-4 rounded-lg text-center shadow-sm border border-blue-100">
                            <p class="text-3xl font-bold text-blue-600">{{ $devoirs->where('statut', 'approuve')->count() }}</p>
                            <p class="text-sm text-gray-600 mt-1">Approuvés</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg text-center shadow-sm border border-green-100">
                            <p class="text-3xl font-bold text-green-600">{{ $devoirs->where('statut', 'confirmé')->count() }}</p>
                            <p class="text-sm text-gray-600 mt-1">Confirmés</p>
                        </div>
                        <div class="bg-red-50 p-4 rounded-lg text-center shadow-sm border border-red-100">
                            <p class="text-3xl font-bold text-red-600">{{ $devoirs->where('statut', 'refuse')->count() }}</p>
                            <p class="text-sm text-gray-600 mt-1">Refusés</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section Mes Cours -->
            <div class="bg-white overflow-hidden shadow-xl rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-gray-800">Mes Cours</h3>
                        <a href="{{ route('enseignant.cours.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center">
                            Voir tous
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        @php
                            $coursResponsable = App\Models\Cours::where('enseignants_id', Auth::user()->enseignant->id)->get();
                            $coursAssocies = Auth::user()->enseignant->cours;
                            $allCours = $coursResponsable->merge($coursAssocies)->unique('id');
                        @endphp
                        @if(count($allCours) > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($allCours->take(6) as $cours)
                                    <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
                                        <h4 class="font-semibold text-lg mb-2 text-gray-800">{{ $cours->intitule }}</h4>
                                        <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $cours->description }}</p>
                                        <div class="space-y-2">
                                            <div class="flex flex-wrap gap-1">
                                                <span class="text-xs font-medium text-gray-500">Filières:</span>
                                                @if($cours->filieres->count() > 0)
                                                    @foreach($cours->filieres as $filiere)
                                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                                            {{ $filiere->code }}
                                                        </span>
                                                    @endforeach
                                                @else
                                                    <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">
                                                        Non spécifié
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex flex-wrap gap-1">
                                                <span class="text-xs font-medium text-gray-500">Niveaux:</span>
                                                @if($cours->niveaux->count() > 0)
                                                    @foreach($cours->niveaux as $niveau)
                                                        <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs">
                                                            {{ $niveau->code }}
                                                        </span>
                                                    @endforeach
                                                @else
                                                    <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">
                                                        Non spécifié
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-gray-500">Vous n'avez aucun cours assigné pour le moment.</p>
                                <p class="text-gray-500 text-sm mt-2">Contactez l'administration si vous pensez qu'il s'agit d'une erreur.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Section Devoirs Récents -->
            <div class="bg-white overflow-hidden shadow-xl rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-gray-800">Devoirs Récents</h3>
                        <a href="{{ route('enseignant.devoirs.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center">
                            Voir tous
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                    @if(isset($devoirs) && count($devoirs) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white rounded-lg overflow-hidden">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="py-3 px-4 border-b text-left font-medium text-gray-700">Devoir</th>
                                        <th class="py-3 px-4 border-b text-left font-medium text-gray-700">Cours</th>
                                        <th class="py-3 px-4 border-b text-left font-medium text-gray-700">Date</th>
                                        <th class="py-3 px-4 border-b text-left font-medium text-gray-700">Statut</th>
                                        <th class="py-3 px-4 border-b text-left font-medium text-gray-700">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($devoirs->take(5) as $devoir)
                                        <tr class="hover:bg-gray-50">
                                            <td class="py-3 px-4 border-b">
                                                <div>
                                                    <p class="font-medium text-gray-800">{{ $devoir->nom_devoir }}</p>
                                                    <p class="text-xs text-gray-500">{{ $devoir->type }} - {{ $devoir->niveau }}</p>
                                                </div>
                                            </td>
                                            <td class="py-3 px-4 border-b text-gray-700">{{ $devoir->nom_cours ?? ($devoir->cours->intitule ?? 'N/A') }}</td>
                                            <td class="py-3 px-4 border-b">
                                                <div>
                                                    <p class="text-gray-700">{{ $devoir->date_heure ? \Carbon\Carbon::parse($devoir->date_heure)->format('d/m/Y') : 'N/A' }}</p>
                                                    <p class="text-xs text-gray-500">{{ $devoir->date_heure ? \Carbon\Carbon::parse($devoir->date_heure)->format('H:i') : '' }}</p>
                                                </div>
                                            </td>
                                            <td class="py-3 px-4 border-b">
                                                @if($devoir->statut == 'confirmé')
                                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 font-medium">Confirmé</span>
                                                @elseif($devoir->statut == 'en_attente')
                                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 font-medium">En attente</span>
                                                @elseif($devoir->statut == 'annulé')
                                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800 font-medium">Annulé</span>
                                                @else
                                                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800 font-medium">{{ $devoir->statut }}</span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-4 border-b">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('enseignant.devoirs.edit', $devoir->id) }}" class="text-blue-600 hover:text-blue-900 p-1 rounded-full hover:bg-blue-50">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>
                                                    @if($devoir->statut == 'en_attente')
                                                        <form action="{{ route('enseignant.devoirs.destroy', $devoir->id) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900 p-1 rounded-full hover:bg-red-50" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce devoir?')">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500">Vous n'avez créé aucun devoir récemment.</p>
                            <a href="{{ route('enseignant.devoirs.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 border border-transparent rounded-md font-medium text-sm text-white shadow-sm transition duration-150 ease-in-out">
                                Créer un devoir
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection