@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Détails du devoir') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            <p>{{ session('error') }}</p>
        </div>
        @endif

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800"></h1>
            <div class="flex space-x-2">
                <a href="{{ route('etudiant.devoirs.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Retour à la liste
                </a>
                <a href="{{ route('etudiant.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Tableau de bord
                </a>
            </div>
        </div>

        <!-- Disposition principale en deux colonnes pour desktop -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6">
            <!-- Colonne principale - Informations du devoir -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Carte d'informations principales -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Informations du devoir
                        </h2>
                        <span class="px-3 py-1 rounded-full text-sm font-medium 
                            @if($devoir->statut == 'en_attente') bg-yellow-100 text-yellow-800
                            @elseif($devoir->statut == 'confirmé') bg-green-100 text-green-800
                            @elseif($devoir->statut == 'terminé') bg-blue-100 text-blue-800
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $devoir->statut)) }}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-3">
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <p class="text-sm text-gray-500">Cours</p>
                                <p class="font-medium text-gray-800">{{ $devoir->cours->intitule }}</p>
                            </div>
                            
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <p class="text-sm text-gray-500">Enseignant</p>
                                <p class="font-medium text-gray-800">{{ $devoir->enseignant->nom }} {{ $devoir->enseignant->prenom }}</p>
                            </div>
                            
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <p class="text-sm text-gray-500">Date et heure</p>
                                <p class="font-medium text-green-600">{{ \Carbon\Carbon::parse($devoir->date_heure)->isoFormat('dddd D MMMM YYYY') }} à {{ \Carbon\Carbon::parse($devoir->date_heure)->format('H:i') }}</p>
                            </div>
                            
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <p class="text-sm text-gray-500">Durée</p>
                                <p class="font-medium text-gray-800">{{ $devoir->duree_minutes }} minutes</p>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <p class="text-sm text-gray-500">Année académique</p>
                                <p class="font-medium text-gray-800">{{ $devoir->anneeAcademique->libelle ?? date('Y').'-'.(date('Y')+1) }}</p>
                            </div>
                            
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <p class="text-sm text-gray-500">Semestre</p>
                                <p class="font-medium text-gray-800">{{ $devoir->semestre->libelle ?? 'S'.$devoir->semestre_id }}</p>
                            </div>
                            
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <p class="text-sm text-gray-500">Type</p>
                                <p class="font-medium text-gray-800">{{ $devoir->type }}</p>
                            </div>
                            
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <p class="text-sm text-gray-500">Niveau</p>
                                <p class="font-medium text-gray-800">{{ $devoir->niveau }}</p>
                            </div>
                        </div>
                    </div>

                <!-- Carte des salles assignées -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Salles assignées
                    </h3>
                    
                    @if(count($devoir->salles) > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($devoir->salles as $salle)
                        <div class="bg-indigo-50 p-3 rounded-lg border border-indigo-100 hover:shadow-md transition-shadow">
                            <p class="font-medium text-indigo-700">{{ $salle->nom }}</p>
                            <p class="text-sm text-gray-600">Capacité: {{ $salle->capacite }} places</p>
                            @if($salle->localisation)
                            <p class="text-sm text-gray-600">Localisation: {{ $salle->localisation }}</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4 bg-gray-50 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                        </svg>
                        <p class="text-gray-500">Aucune salle n'a encore été assignée pour ce devoir.</p>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Colonne latérale - Informations supplémentaires -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Carte de statut et compte à rebours -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Compte à rebours
                    </h3>
                    
                    @if($devoir->date_heure && \Carbon\Carbon::parse($devoir->date_heure)->isFuture())
                    <div class="bg-gradient-to-r from-indigo-50 to-blue-50 p-4 rounded-lg text-center" id="countdown-container">
                        <div class="flex justify-center items-center space-x-1 mb-3">
                            <div class="bg-white p-3 rounded-lg shadow-sm border border-indigo-100 flex flex-col items-center justify-center w-20">
                                <div id="days" class="text-3xl font-bold text-indigo-700">--</div>
                                <div class="text-xs text-gray-500 uppercase tracking-wider mt-1">Jours</div>
                            </div>
                            <div class="text-indigo-400 text-2xl font-bold">:</div>
                            <div class="bg-white p-3 rounded-lg shadow-sm border border-indigo-100 flex flex-col items-center justify-center w-20">
                                <div id="hours" class="text-3xl font-bold text-indigo-700">--</div>
                                <div class="text-xs text-gray-500 uppercase tracking-wider mt-1">Heures</div>
                            </div>
                            <div class="text-indigo-400 text-2xl font-bold">:</div>
                            <div class="bg-white p-3 rounded-lg shadow-sm border border-indigo-100 flex flex-col items-center justify-center w-20">
                                <div id="minutes" class="text-3xl font-bold text-indigo-700">--</div>
                                <div class="text-xs text-gray-500 uppercase tracking-wider mt-1">Min</div>
                            </div>
                            <div class="text-indigo-400 text-2xl font-bold">:</div>
                            <div class="bg-white p-3 rounded-lg shadow-sm border border-indigo-100 flex flex-col items-center justify-center w-20">
                                <div id="seconds" class="text-3xl font-bold text-indigo-700">--</div>
                                <div class="text-xs text-gray-500 uppercase tracking-wider mt-1">Sec</div>
                            </div>
                        </div>
                        <p class="text-sm text-indigo-700 font-medium bg-white py-2 px-4 rounded-full inline-block shadow-sm border border-indigo-100">
                            avant le début du devoir
                        </p>
                    </div>
                    @elseif($devoir->date_heure && \Carbon\Carbon::parse($devoir->date_heure)->isPast() && \Carbon\Carbon::parse($devoir->date_heure)->addMinutes($devoir->duree_minutes)->isFuture())
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-4 rounded-lg text-center">
                        <div class="text-lg font-bold text-green-700 mb-2">Devoir en cours</div>
                        <div class="bg-white py-3 px-4 rounded-lg inline-block shadow-sm border border-green-100">
                            <div class="flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-xl font-bold text-green-700" id="remaining-time">--:--:--</span>
                            </div>
                            <p class="text-xs text-green-600 mt-1">temps restant</p>
                        </div>
                    </div>
                    @else
                    <div class="bg-gradient-to-r from-gray-50 to-slate-50 p-4 rounded-lg text-center">
                        <div class="text-lg font-bold text-gray-700 mb-2">Devoir terminé</div>
                        <div class="bg-white py-2 px-4 rounded-lg inline-block shadow-sm border border-gray-200">
                            <p class="text-sm text-gray-600">Terminé le {{ \Carbon\Carbon::parse($devoir->date_heure)->addMinutes($devoir->duree_minutes)->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Carte d'informations importantes -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        Important
                    </h3>
                    
                    <div class="bg-red-50 p-4 rounded-lg">
                        <ul class="list-disc pl-5 space-y-2 text-red-700">
                            <li>Assurez-vous d'arriver au moins 30 minutes avant le début du devoir.</li>
                            <li>Apportez votre carte d'étudiant et une pièce d'identité.</li>
                            <li>Les téléphones portables doivent être éteints et rangés.</li>
                            <li>Aucun document n'est autorisé sauf indication contraire de l'enseignant.</li>
                            <li>En cas de retard supérieur à 5 minutes, l'accès à la salle pourrait vous être refusé.</li>
                        </ul>
                    </div>
                </div>
                
                @if($devoir->statut == 'confirmé' && $devoir->code_QR)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Code QR pour suivi du devoir
                    </h3>
                    
                    <div class="bg-green-50 p-4 rounded-lg text-center border border-green-100">
                        <img src="{{ asset('qrcodes/' . $devoir->code_QR) }}" alt="Code QR du devoir" class="w-48 h-48 mx-auto mb-3 border p-2 bg-white rounded shadow-sm" id="qrCodeImage">
                        <p class="text-sm text-green-700 mb-3">Présentez ce code QR pour enregistrer votre présence au devoir</p>
                        
                        <a href="{{ asset('qrcodes/' . $devoir->code_QR) }}" download="code_qr_devoir_{{ $devoir->id }}.png" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Télécharger le code QR
                        </a>
                    </div>
                </div>
                @endif

                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-800 mb-4 pb-2 border-b">Document</h3>
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-blue-700">Le sujet du devoir sera distribué au début de l'épreuve. Assurez-vous d'arriver à l'heure pour recevoir votre copie.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Script pour le compte à rebours
    document.addEventListener('DOMContentLoaded', function() {
        const devoirDate = new Date("{{ $devoir->date_heure }}").getTime();
        const devoirEndDate = new Date("{{ \Carbon\Carbon::parse($devoir->date_heure)->addMinutes($devoir->duree_minutes) }}").getTime();
        const now = new Date().getTime();
        
        // Mise à jour du compte à rebours
        function updateCountdown() {
            const now = new Date().getTime();
            const distance = devoirDate - now;
            const remainingTime = devoirEndDate - now;
            
            // Si le devoir n'a pas encore commencé
            if (distance > 0) {
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                document.getElementById("days").innerHTML = days;
                document.getElementById("hours").innerHTML = hours;
                document.getElementById("minutes").innerHTML = minutes;
                document.getElementById("seconds").innerHTML = seconds;
            } 
            // Si le devoir est en cours
            else if (remainingTime > 0) {
                const hours = Math.floor(remainingTime / (1000 * 60 * 60));
                const minutes = Math.floor((remainingTime % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);
                
                const formattedTime = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                const remainingTimeElement = document.getElementById("remaining-time");
                if (remainingTimeElement) {
                    remainingTimeElement.innerHTML = formattedTime;
                }
            }
        }
        
        // Mettre à jour le compte à rebours toutes les secondes
        if (devoirDate > now || devoirEndDate > now) {
            updateCountdown();
            setInterval(updateCountdown, 1000);
        }
    });
</script>
@endsection
