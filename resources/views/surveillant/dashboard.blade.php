@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Tableau de bord Surveillant') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Messages de notification -->
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Cartes de statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- Total des devoirs -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="text-gray-900 text-xl font-semibold mb-2">Total des devoirs</div>
                    <div class="text-3xl font-bold text-blue-600">{{ $totalDevoirs }}</div>
                </div>
            </div>

            <!-- Devoirs en attente -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="text-gray-900 text-xl font-semibold mb-2">En attente</div>
                    <div class="text-3xl font-bold text-yellow-600">{{ $devoirsEnAttente }}</div>
                </div>
            </div>

            <!-- Devoirs acceptés -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="text-gray-900 text-xl font-semibold mb-2">Acceptés</div>
                    <div class="text-3xl font-bold text-green-600">{{ $devoirsAcceptes }}</div>
                </div>
            </div>

            <!-- Devoirs refusés -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="text-gray-900 text-xl font-semibold mb-2">Refusés</div>
                    <div class="text-3xl font-bold text-red-600">{{ $devoirsRefuses }}</div>
                </div>
            </div>
        </div>

        <!-- Devoirs à venir -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
            <div class="p-6 bg-white border-b border-gray-200">
                <h3 class="text-lg font-semibold mb-4">Devoirs à venir</h3>
                @if($devoirsAVenir->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Devoir</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Heure</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Salle</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enseignant</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($devoirsAVenir as $devoir)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $devoir->nom_devoir }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($devoir->date_heure)->format('d/m/Y H:i') }}
                                            <div class="text-xs text-gray-500">
                                                Durée: {{ $devoir->duree_minutes ?? '?' }} min
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($devoir->salles->count() > 0)
                                                <div>
                                                    @foreach($devoir->salles as $salle)
                                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 mb-1">
                                                            {{ $salle->nom }}
                                                        </span>
                                                        @if(!$loop->last) <br> @endif
                                                    @endforeach
                                                </div>
                                            @elseif($devoir->salle)
                                                {{ $devoir->salle->nom }}
                                            @else
                                                <span class="text-gray-400">Non assignée</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $devoir->enseignant->nom ?? 'N/A' }} {{ $devoir->enseignant->prenom ?? '' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($devoir->pivot->statut === 'en_attente')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    En attente
                                                </span>
                                            @elseif($devoir->pivot->statut === 'accepte')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Accepté
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Refusé
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            @if($devoir->pivot->statut === 'en_attente')
                                                <form action="{{ route('surveillant.devoirs.accepter', $devoir->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 hover:text-green-900 mr-3">Accepter</button>
                                                </form>
                                                <button onclick="ouvrirModalRefus({{ $devoir->id }})" class="text-red-600 hover:text-red-900">
                                                    Refuser
                                                </button>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500">Aucun devoir à venir.</p>
                @endif
            </div>
        </div>

        <!-- Derniers devoirs passés -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h3 class="text-lg font-semibold mb-4">Derniers devoirs passés</h3>
                @if($devoirsPassés->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Devoir</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Heure</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Salle</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enseignant</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($devoirsPassés as $devoir)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $devoir->nom_devoir }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($devoir->date_heure)->format('d/m/Y H:i') }}
                                            <div class="text-xs text-gray-500">
                                                Durée: {{ $devoir->duree_minutes ?? '?' }} min
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($devoir->salles->count() > 0)
                                                <div>
                                                    @foreach($devoir->salles as $salle)
                                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 mb-1">
                                                            {{ $salle->nom }}
                                                        </span>
                                                        @if(!$loop->last) <br> @endif
                                                    @endforeach
                                                </div>
                                            @elseif($devoir->salle)
                                                {{ $devoir->salle->nom }}
                                            @else
                                                <span class="text-gray-400">Non assignée</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $devoir->enseignant->nom ?? 'N/A' }} {{ $devoir->enseignant->prenom ?? '' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($devoir->pivot->statut === 'en_attente')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    En attente
                                                </span>
                                            @elseif($devoir->pivot->statut === 'accepte')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Accepté
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Refusé
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500">Aucun devoir passé.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal de refus -->
<div id="modalRefus" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="formRefus" action="" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <!-- Heroicon name: outline/exclamation -->
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Refuser ce devoir
                            </h3>
                            <div class="mt-1 text-sm text-gray-500" id="devoir-details">
                                <!-- Les détails du devoir seront insérés ici par JavaScript -->
                            </div>
                            <div class="mt-4">
                                <label for="motif-predefined" class="block text-sm font-medium text-gray-700">Motif standard (optionnel)</label>
                                <select id="motif-predefined" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" onchange="updateCommentaire(this.value)">
                                    <option value="">Sélectionnez un motif ou écrivez le vôtre</option>
                                    <option value="Indisponible à cette date">Indisponible à cette date</option>
                                    <option value="Conflit d'horaire avec un autre devoir">Conflit d'horaire avec un autre devoir</option>
                                    <option value="En congé durant cette période">En congé durant cette période</option>
                                    <option value="Trop de devoirs à surveiller cette semaine">Trop de devoirs à surveiller cette semaine</option>
                                    <option value="Trop éloigné de mon lieu de résidence">Trop éloigné de mon lieu de résidence</option>
                                </select>
                            </div>
                            <div class="mt-3">
                                <label for="commentaire" class="block text-sm font-medium text-gray-700">Commentaire détaillé <span class="text-red-500">*</span></label>
                                <textarea name="commentaire" id="commentaire" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Veuillez expliquer la raison du refus..." required></textarea>
                                <p class="mt-1 text-xs text-gray-500">Ce commentaire sera visible par l'enseignant</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Confirmer le refus
                    </button>
                    <button type="button" onclick="fermerModalRefus()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let devoirsData = {};
    
    @foreach($devoirsAVenir as $devoir)
        devoirsData[{{ $devoir->id }}] = {
            nom: "{{ $devoir->nom_devoir }}",
            date: "{{ \Carbon\Carbon::parse($devoir->date_heure)->format('d/m/Y H:i') }}",
            salle: "{{ $devoir->salle->nom ?? 'N/A' }}",
            enseignant: "{{ $devoir->enseignant->nom ?? 'N/A' }} {{ $devoir->enseignant->prenom ?? '' }}"
        };
    @endforeach
    
    function ouvrirModalRefus(devoirId) {
        const modal = document.getElementById('modalRefus');
        const form = document.getElementById('formRefus');
        const detailsContainer = document.getElementById('devoir-details');
        
        // Mettre à jour l'action du formulaire
        form.action = "{{ url('/surveillant/devoirs') }}/" + devoirId + "/refuser";
        
        // Afficher les détails du devoir
        if(devoirsData[devoirId]) {
            const details = devoirsData[devoirId];
            detailsContainer.innerHTML = `
                <div class="mt-2 p-3 bg-gray-50 rounded-md">
                    <p><strong>Devoir:</strong> ${details.nom}</p>
                    <p><strong>Date/Heure:</strong> ${details.date}</p>
                    <p><strong>Salle:</strong> ${details.salle}</p>
                    <p><strong>Enseignant:</strong> ${details.enseignant}</p>
                </div>
            `;
        }
        
        // Réinitialiser les champs
        document.getElementById('motif-predefined').value = '';
        document.getElementById('commentaire').value = '';
        
        // Afficher la modal
        modal.classList.remove('hidden');
    }

    function fermerModalRefus() {
        const modal = document.getElementById('modalRefus');
        modal.classList.add('hidden');
    }
    
    function updateCommentaire(value) {
        if (value) {
            const commentaireElem = document.getElementById('commentaire');
            commentaireElem.value = value;
        }
    }
</script>
@endpush
@endsection
