@extends('layouts.admin')

@section('title', 'Modifier le Cours')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Modifier le cours</h6>
                <a href="{{ route('admin.cours.show', $cours) }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form action="{{ route('admin.cours.update', $cours) }}" method="POST" id="cours-form">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group mb-4">
                        <label for="intitule">Intitulé du cours <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('intitule') is-invalid @enderror" id="intitule" name="intitule" value="{{ old('intitule', $cours->intitule) }}" required>
                        @error('intitule')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Exemple: Algorithmique et Structures de Données</small>
                    </div>
                    
                    <div class="form-group mb-4">
                        <label for="description">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" required>{{ old('description', $cours->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="d-flex justify-content-between">
                            <small class="form-text text-muted">Décrivez le contenu et les objectifs du cours</small>
                            <small id="description-counter" class="text-muted">0 caractères</small>
                        </div>
                    </div>
                    
                    <div class="form-group mb-4">
                        <label for="enseignants_id">Enseignant <span class="text-danger">*</span></label>
                        <select class="form-control select2 @error('enseignants_id') is-invalid @enderror" id="enseignants_id" name="enseignants_id" required>
                            <option value="">Sélectionner un enseignant</option>
                            @foreach($enseignants as $enseignant)
                                <option value="{{ $enseignant->id }}" {{ old('enseignants_id', $cours->enseignants_id) == $enseignant->id ? 'selected' : '' }}>
                                    {{ $enseignant->nom }} {{ $enseignant->prenom }} - {{ $enseignant->specialite ?? 'Non spécifié' }}
                                </option>
                            @endforeach
                        </select>
                        @error('enseignants_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="filieres">Filières <span class="text-danger">*</span></label>
                                <select class="form-control select2-multiple @error('filieres') is-invalid @enderror" id="filieres" name="filieres[]" multiple required>
                                    @foreach($filieres as $filiere)
                                        <option value="{{ $filiere->id }}" {{ (is_array(old('filieres', $filieresIds)) && in_array($filiere->id, old('filieres', $filieresIds))) ? 'selected' : '' }}>
                                            {{ $filiere->code }} - {{ $filiere->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('filieres')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="niveaux">Niveaux <span class="text-danger">*</span></label>
                                <select class="form-control select2-multiple @error('niveaux') is-invalid @enderror" id="niveaux" name="niveaux[]" multiple required>
                                    @foreach($niveaux as $niveau)
                                        <option value="{{ $niveau->id }}" {{ (is_array(old('niveaux', $niveauxIds)) && in_array($niveau->id, old('niveaux', $niveauxIds))) ? 'selected' : '' }}>
                                            {{ $niveau->code }} - {{ $niveau->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('niveaux')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mt-4 text-center">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                        </button>
                        <a href="{{ route('admin.cours.show', $cours) }}" class="btn btn-secondary btn-lg px-5 ml-2">
                            <i class="fas fa-times mr-2"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Aide et informations</h6>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h5><i class="fas fa-lightbulb text-warning"></i> Conseils</h5>
                    <ul class="pl-3">
                        <li>Vérifiez que toutes les informations sont correctes</li>
                        <li>Assurez-vous que les filières et niveaux sélectionnés sont appropriés</li>
                        <li>La description doit être claire et concise</li>
                    </ul>
                </div>
                
                <div class="mb-4">
                    <h5><i class="fas fa-history text-info"></i> Historique</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <small class="text-muted">Créé le</small><br>
                            {{ $cours->created_at->format('d/m/Y à H:i') }}
                        </li>
                        <li>
                            <small class="text-muted">Dernière modification</small><br>
                            {{ $cours->updated_at->format('d/m/Y à H:i') }}
                        </li>
                    </ul>
                </div>
                
                <div>
                    <h5><i class="fas fa-exclamation-triangle text-danger"></i> Attention</h5>
                    <p>La modification des filières et niveaux peut affecter les étudiants inscrits et l'emploi du temps.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .select2-container .select2-selection--single {
        height: 38px !important;
        padding: 5px 0;
    }
    
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #4e73df;
        border-color: #4e73df;
        color: white;
        padding: 2px 8px;
    }
    
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: white;
        margin-right: 5px;
    }
    
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #4e73df;
    }
</style>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialisation de Select2 pour une meilleure UX sur les sélecteurs
        $('.select2').select2({
            placeholder: "Sélectionner une option",
            allowClear: true,
            width: '100%'
        });
        
        $('.select2-multiple').select2({
            placeholder: "Sélectionner une ou plusieurs options",
            allowClear: true,
            width: '100%'
        });
        
        // Compteur de caractères pour la description
        $('#description').on('input', function() {
            var charCount = $(this).val().length;
            $('#description-counter').text(charCount + ' caractères');
            
            if (charCount > 500) {
                $('#description-counter').addClass('text-danger');
            } else {
                $('#description-counter').removeClass('text-danger');
            }
        });
        
        // Déclencher le compteur au chargement si la description contient déjà du texte
        if ($('#description').val()) {
            $('#description').trigger('input');
        }
        
        // Validation côté client avant soumission
        $('#cours-form').on('submit', function(e) {
            var isValid = true;
            
            // Vérifier que l'intitulé n'est pas vide
            if (!$('#intitule').val().trim()) {
                $('#intitule').addClass('is-invalid');
                isValid = false;
            }
            
            // Vérifier que la description n'est pas vide
            if (!$('#description').val().trim()) {
                $('#description').addClass('is-invalid');
                isValid = false;
            }
            
            // Vérifier qu'un enseignant est sélectionné
            var enseignantSelect = $('#enseignants_id');
            if (!enseignantSelect.val()) {
                enseignantSelect.next('.select2-container').find('.select2-selection').addClass('border-danger');
                // Ajouter un message d'erreur personnalisé
                if (!enseignantSelect.next('.invalid-feedback').length) {
                    enseignantSelect.after('<div class="invalid-feedback">Veuillez sélectionner un enseignant</div>');
                }
                isValid = false;
            } else {
                enseignantSelect.next('.select2-container').find('.select2-selection').removeClass('border-danger');
                enseignantSelect.next('.invalid-feedback').remove();
            }
            
            // Vérifier qu'au moins une filière est sélectionnée
            if (!$('#filieres').val() || $('#filieres').val().length === 0) {
                $('#filieres').next('.select2-container').find('.select2-selection').addClass('border-danger');
                isValid = false;
            }
            
            // Vérifier qu'au moins un niveau est sélectionné
            if (!$('#niveaux').val() || $('#niveaux').val().length === 0) {
                $('#niveaux').next('.select2-container').find('.select2-selection').addClass('border-danger');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
                // Afficher un message d'erreur général
                if (!$('.alert-danger').length) {
                    $('.card-body').prepend('<div class="alert alert-danger">Veuillez remplir tous les champs obligatoires</div>');
                }
                // Faire défiler jusqu'au premier champ invalide
                $('html, body').animate({
                    scrollTop: $('.is-invalid').first().offset().top - 100
                }, 500);
            }
        });
        
        // Réinitialiser les erreurs de validation lors de la saisie
        $('.form-control, .select2, .select2-multiple').on('input change', function() {
            $(this).removeClass('is-invalid');
            $(this).next('.select2-container').find('.select2-selection').removeClass('border-danger');
        });
    });
</script>
@endsection
