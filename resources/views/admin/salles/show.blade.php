@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Détails de la salle') }}
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

                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold">{{ $salle->nom }}</h3>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.salles.edit', $salle->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            <i class="fas fa-edit mr-2"></i>Modifier
                        </a>
                        <form action="{{ route('admin.salles.destroy', $salle->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette salle?')">
                                <i class="fas fa-trash mr-2"></i>Supprimer
                            </button>
                        </form>
                    </div>
                </div>

                <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Informations générales</h4>
                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                <div class="mb-4">
                                    <p class="text-sm text-gray-500">Nom</p>
                                    <p class="text-lg font-medium">{{ $salle->nom }}</p>
                                </div>
                                <div class="mb-4">
                                    <p class="text-sm text-gray-500">Type</p>
                                    <p class="text-lg font-medium">{{ $salle->type }}</p>
                                </div>
                                <div class="mb-4">
                                    <p class="text-sm text-gray-500">Capacité</p>
                                    <p class="text-lg font-medium">{{ $salle->capacite }} places</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Localisation</p>
                                    <p class="text-lg font-medium">{{ $salle->localisation }}</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Statut et description</h4>
                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                <div class="mb-4">
                                    <p class="text-sm text-gray-500">Disponibilité</p>
                                    @if($salle->disponible)
                                        <p class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3" />
                                            </svg>
                                            Disponible
                                        </p>
                                    @else
                                        <p class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-red-400" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3" />
                                            </svg>
                                            Non disponible
                                        </p>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Description</p>
                                    <p class="text-base">{{ $salle->description ?: 'Aucune description disponible' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Devoirs programmés dans cette salle</h4>
                        <div class="bg-white p-4 rounded-lg shadow-sm">
                            @if($salle->devoirs->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Devoir</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cours</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($salle->devoirs as $devoir)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $devoir->nom_devoir }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $devoir->cours->intitule ?? 'N/A' }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $devoir->date_devoir ? date('d/m/Y H:i', strtotime($devoir->date_devoir)) : 'Non programmé' }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        @if($devoir->statut == 'en_attente')
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                                En attente
                                                            </span>
                                                        @elseif($devoir->statut == 'confirme')
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                                Confirmé
                                                            </span>
                                                        @elseif($devoir->statut == 'termine')
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                                Terminé
                                                            </span>
                                                        @else
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                                {{ $devoir->statut }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-gray-500 text-center py-4">Aucun devoir n'est programmé dans cette salle pour le moment.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
