@extends('layouts.app')

@section('content')
<div class="container px-6 mx-auto grid">
    <h2 class="my-6 text-2xl font-semibold text-gray-700">
        Modifier les associations du cours: {{ $cours->intitule }}
    </h2>

    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md">
        <form action="{{ route('admin.cours_associations.update', $cours) }}" method="POST" id="associationsForm">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <h3 class="text-lg font-medium text-gray-700 mb-2">Associations actuelles</h3>
                <div id="associationsContainer">
                    @if(count($coursAssociations) > 0)
                        @foreach($coursAssociations as $index => $association)
                            <div class="flex flex-wrap items-center mb-2 association-row">
                                <div class="w-full md:w-5/12 pr-2 mb-2 md:mb-0">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Filière
                                    </label>
                                    <select name="associations[{{ $index }}][filiere_id]" class="block w-full mt-1 text-sm border-gray-300 rounded-md focus:border-purple-400 focus:outline-none focus:shadow-outline-purple form-select">
                                        @foreach($filieres as $filiere)
                                            <option value="{{ $filiere->id }}" {{ $filiere->id == $association['filiere_id'] ? 'selected' : '' }}>
                                                {{ $filiere->code }} - {{ $filiere->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="w-full md:w-5/12 pr-2 mb-2 md:mb-0">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Niveau
                                    </label>
                                    <select name="associations[{{ $index }}][niveau_id]" class="block w-full mt-1 text-sm border-gray-300 rounded-md focus:border-purple-400 focus:outline-none focus:shadow-outline-purple form-select">
                                        @foreach($niveaux as $niveau)
                                            <option value="{{ $niveau->id }}" {{ $niveau->id == $association['niveau_id'] ? 'selected' : '' }}>
                                                {{ $niveau->code }} - {{ $niveau->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="w-full md:w-2/12 flex items-end justify-center mt-2 md:mt-0">
                                    <button type="button" class="px-3 py-1 text-sm text-red-600 bg-red-100 rounded-md hover:bg-red-200 remove-association">
                                        <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Supprimer
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="flex flex-wrap items-center mb-2 association-row">
                            <div class="w-full md:w-5/12 pr-2 mb-2 md:mb-0">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Filière
                                </label>
                                <select name="associations[0][filiere_id]" class="block w-full mt-1 text-sm border-gray-300 rounded-md focus:border-purple-400 focus:outline-none focus:shadow-outline-purple form-select">
                                    @foreach($filieres as $filiere)
                                        <option value="{{ $filiere->id }}">
                                            {{ $filiere->code }} - {{ $filiere->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-full md:w-5/12 pr-2 mb-2 md:mb-0">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Niveau
                                </label>
                                <select name="associations[0][niveau_id]" class="block w-full mt-1 text-sm border-gray-300 rounded-md focus:border-purple-400 focus:outline-none focus:shadow-outline-purple form-select">
                                    @foreach($niveaux as $niveau)
                                        <option value="{{ $niveau->id }}">
                                            {{ $niveau->code }} - {{ $niveau->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-full md:w-2/12 flex items-end justify-center mt-2 md:mt-0">
                                <button type="button" class="px-3 py-1 text-sm text-red-600 bg-red-100 rounded-md hover:bg-red-200 remove-association">
                                    <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Supprimer
                                </button>
                            </div>
                        </div>
                    @endif
                </div>

                <button type="button" id="addAssociation" class="mt-2 px-3 py-1 text-sm text-green-600 bg-green-100 rounded-md hover:bg-green-200">
                    <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Ajouter une association
                </button>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('admin.cours_associations.index') }}" class="px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition-colors duration-150 bg-white border border-gray-300 rounded-lg active:bg-white hover:bg-gray-100 focus:outline-none focus:shadow-outline-gray mr-2">
                    Annuler
                </a>
                <button type="submit" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('associationsContainer');
            const addButton = document.getElementById('addAssociation');
            
            // Ajouter une nouvelle association
            addButton.addEventListener('click', function() {
                const rows = container.querySelectorAll('.association-row');
                const newIndex = rows.length;
                
                const template = `
                    <div class="flex flex-wrap items-center mb-2 association-row">
                        <div class="w-full md:w-5/12 pr-2 mb-2 md:mb-0">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Filière
                            </label>
                            <select name="associations[${newIndex}][filiere_id]" class="block w-full mt-1 text-sm border-gray-300 rounded-md focus:border-purple-400 focus:outline-none focus:shadow-outline-purple form-select">
                                @foreach($filieres as $filiere)
                                    <option value="{{ $filiere->id }}">
                                        {{ $filiere->code }} - {{ $filiere->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-full md:w-5/12 pr-2 mb-2 md:mb-0">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Niveau
                            </label>
                            <select name="associations[${newIndex}][niveau_id]" class="block w-full mt-1 text-sm border-gray-300 rounded-md focus:border-purple-400 focus:outline-none focus:shadow-outline-purple form-select">
                                @foreach($niveaux as $niveau)
                                    <option value="{{ $niveau->id }}">
                                        {{ $niveau->code }} - {{ $niveau->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-full md:w-2/12 flex items-end justify-center mt-2 md:mt-0">
                            <button type="button" class="px-3 py-1 text-sm text-red-600 bg-red-100 rounded-md hover:bg-red-200 remove-association">
                                <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Supprimer
                            </button>
                        </div>
                    </div>
                `;
                
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = template;
                container.appendChild(tempDiv.firstElementChild);
                
                // Ajouter l'événement de suppression au nouveau bouton
                setupRemoveButtons();
            });
            
            // Supprimer une association
            function setupRemoveButtons() {
                document.querySelectorAll('.remove-association').forEach(button => {
                    button.addEventListener('click', function() {
                        // Ne pas supprimer s'il n'y a qu'une seule association
                        const rows = document.querySelectorAll('.association-row');
                        if (rows.length > 1) {
                            this.closest('.association-row').remove();
                            // Réindexer les champs pour maintenir un index séquentiel
                            reindexFields();
                        } else {
                            alert('Vous devez avoir au moins une association.');
                        }
                    });
                });
            }
            
            // Réindexer les champs après suppression
            function reindexFields() {
                const rows = document.querySelectorAll('.association-row');
                rows.forEach((row, index) => {
                    const filiereSelect = row.querySelector('select[name^="associations"][name$="[filiere_id]"]');
                    const niveauSelect = row.querySelector('select[name^="associations"][name$="[niveau_id]"]');
                    
                    filiereSelect.name = `associations[${index}][filiere_id]`;
                    niveauSelect.name = `associations[${index}][niveau_id]`;
                });
            }
            
            // Initialiser les boutons de suppression
            setupRemoveButtons();
        });
    </script>
@endsection
