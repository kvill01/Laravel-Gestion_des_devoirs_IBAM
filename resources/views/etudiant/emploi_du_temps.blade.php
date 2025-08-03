@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Emploi du Temps') }}
    </h2>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                @if(isset($error) && $error)
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                        <p>{{ $error }}</p>
                    </div>
                @endif

                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-lg font-bold">
                            Section {{ $filiere->nom ?? 'Non définie' }} - 
                            {{ $niveau->nom ?? 'Non défini' }} - 
                            {{ $semestre->libelle ?? 'Non défini' }}
                        </h3>
                        <p class="text-gray-600">
                            Semaine du {{ $dateDebut->format('d/m/Y') }} au {{ $dateFin->format('d/m/Y') }}
                        </p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('etudiant.emploi-du-temps.semaine', ['date' => $dateDebut->copy()->subWeek()->format('Y-m-d')]) }}" 
                           class="bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded">
                            <span>Semaine précédente</span>
                        </a>
                        <a href="{{ route('etudiant.emploi-du-temps') }}" 
                           class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">
                            <span>Cette semaine</span>
                        </a>
                        <a href="{{ route('etudiant.emploi-du-temps.semaine', ['date' => $dateDebut->copy()->addWeek()->format('Y-m-d')]) }}" 
                           class="bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded">
                            <span>Semaine suivante</span>
                        </a>
                    </div>
                </div>

                <!-- Emploi du temps -->
                @if(!isset($emploiDuTemps) || count(array_filter($emploiDuTemps, function($jourCours) { return !empty($jourCours['07:30-12:30']) || !empty($jourCours['14:00-18:00']); })) === 0)
                    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4" role="alert">
                        <p class="font-bold">Aucun cours trouvé pour cette semaine</p>
                        <p>Il n'y a pas de cours programmés pour vous durant cette période.</p>
                        <p class="mt-2">Si vous pensez qu'il s'agit d'une erreur, veuillez contacter l'administration.</p>
                    </div>
                @else
                <div class="overflow-x-auto">
                    <table class="min-w-full border-collapse">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border bg-gray-100"></th>
                                @foreach(array_keys($emploiDuTemps) as $jour)
                                    @if($jour !== 'Dimanche') <!-- Généralement pas de cours le dimanche -->
                                        <th class="py-2 px-4 border bg-gray-100 text-center">{{ $jour }}</th>
                                    @endif
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Créneau du matin -->
                            <tr>
                                <td class="py-2 px-4 border bg-gray-50 font-medium">07h30 - 12h30</td>
                                @foreach(array_keys($emploiDuTemps) as $jour)
                                    @if($jour !== 'Dimanche')
                                        <td class="py-2 px-4 border text-center h-32 align-top">
                                            @if(isset($emploiDuTemps[$jour]['07:30-12:30']) && count($emploiDuTemps[$jour]['07:30-12:30']) > 0)
                                                @foreach($emploiDuTemps[$jour]['07:30-12:30'] as $cours)
                                                    <div class="bg-blue-50 p-2 mb-2 rounded shadow-sm border-l-4 border-blue-500">
                                                        <p class="font-bold">{{ $cours->cours->intitule ?? 'Cours non défini' }}</p>
                                                        <p class="text-sm">
                                                            {{ Carbon\Carbon::parse($cours->heure_debut)->format('H:i') }} - 
                                                            {{ Carbon\Carbon::parse($cours->heure_fin)->format('H:i') }}
                                                        </p>
                                                        <p class="text-sm">{{ $cours->enseignant->nom ?? '' }} {{ $cours->enseignant->prenom ?? '' }}</p>
                                                        <p class="text-xs text-gray-500">{{ $cours->salle->nom ?? 'Salle non définie' }} ({{ $cours->type_cours ?? 'N/A' }})</p>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="h-full flex items-center justify-center text-gray-400">-</div>
                                            @endif
                                        </td>
                                    @endif
                                @endforeach
                            </tr>
                            
                            <!-- Créneau de l'après-midi -->
                            <tr>
                                <td class="py-2 px-4 border bg-gray-50 font-medium">14h00 - 18h00</td>
                                @foreach(array_keys($emploiDuTemps) as $jour)
                                    @if($jour !== 'Dimanche')
                                        <td class="py-2 px-4 border text-center h-32 align-top">
                                            @if(isset($emploiDuTemps[$jour]['14:00-18:00']) && count($emploiDuTemps[$jour]['14:00-18:00']) > 0)
                                                @foreach($emploiDuTemps[$jour]['14:00-18:00'] as $cours)
                                                    <div class="bg-green-50 p-2 mb-2 rounded shadow-sm border-l-4 border-green-500">
                                                        <p class="font-bold">{{ $cours->cours->intitule ?? 'Cours non défini' }}</p>
                                                        <p class="text-sm">
                                                            {{ Carbon\Carbon::parse($cours->heure_debut)->format('H:i') }} - 
                                                            {{ Carbon\Carbon::parse($cours->heure_fin)->format('H:i') }}
                                                        </p>
                                                        <p class="text-sm">{{ $cours->enseignant->nom ?? '' }} {{ $cours->enseignant->prenom ?? '' }}</p>
                                                        <p class="text-xs text-gray-500">{{ $cours->salle->nom ?? 'Salle non définie' }} ({{ $cours->type_cours ?? 'N/A' }})</p>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="h-full flex items-center justify-center text-gray-400">-</div>
                                            @endif
                                        </td>
                                    @endif
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    <p class="text-sm text-gray-600">
                        <span class="font-semibold">Légende :</span>
                        <span class="inline-block ml-2 px-2 py-1 bg-blue-50 border-l-4 border-blue-500 rounded">Cours du matin</span>
                        <span class="inline-block ml-2 px-2 py-1 bg-green-50 border-l-4 border-green-500 rounded">Cours de l'après-midi</span>
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
