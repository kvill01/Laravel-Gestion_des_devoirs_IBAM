@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Gestion des Associations Cours-Filières-Niveaux') }}
    </h2>
@endsection

@section('content')
<div class="container px-6 mx-auto grid">
    <h2 class="my-6 text-2xl font-semibold text-gray-700">
        
    </h2>

    @if(session('success'))
    <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
        {{ session('error') }}
    </div>
    @endif

    <div class="flex justify-end mb-4 space-x-2">
        <a href="{{ route('admin.cours_associations.batch') }}" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
            Association par lot
        </a>
    </div>

    <div class="w-full overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                        <th class="px-4 py-3">Cours</th>
                        <th class="px-4 py-3">Filières</th>
                        <th class="px-4 py-3">Niveaux</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    @forelse($cours as $c)
                    <tr class="text-gray-700">
                        <td class="px-4 py-3 text-sm">
                            {{ $c->intitule }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            @if($c->filieres->count() > 0)
                                <div class="flex flex-wrap gap-1">
                                @foreach($c->filieres as $filiere)
                                    <span class="px-2 py-1 text-xs font-medium leading-4 text-blue-700 bg-blue-100 rounded-full">
                                        {{ $filiere->code }}
                                    </span>
                                @endforeach
                                </div>
                            @else
                                <span class="text-gray-400">Aucune filière associée</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm">
                            @if($c->niveaux->count() > 0)
                                <div class="flex flex-wrap gap-1">
                                @foreach($c->niveaux as $niveau)
                                    <span class="px-2 py-1 text-xs font-medium leading-4 text-green-700 bg-green-100 rounded-full">
                                        {{ $niveau->code }}
                                    </span>
                                @endforeach
                                </div>
                            @else
                                <span class="text-gray-400">Aucun niveau associé</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <div class="flex items-center space-x-4 text-sm">
                                <a href="{{ route('admin.cours_associations.edit', $c) }}" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg focus:outline-none focus:shadow-outline-gray" aria-label="Edit">
                                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr class="text-gray-700">
                        <td colspan="4" class="px-4 py-3 text-sm text-center">
                            Aucun cours trouvé
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
