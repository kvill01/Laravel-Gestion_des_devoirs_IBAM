@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Modifier le devoir</h1>
    <form method="POST" action="{{ route('devoirs.update', $devoir->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="nom_devoir">Nom du devoir</label>
            <input type="text" name="nom_devoir" id="nom_devoir" class="form-control @error('nom_devoir') is-invalid @enderror" value="{{ old('nom_devoir', $devoir->nom_devoir) }}" required>
            @error('nom_devoir')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="sujet_devoir">Sujet du devoir</label>
            <textarea name="sujet_devoir" id="sujet_devoir" class="form-control @error('sujet_devoir') is-invalid @enderror" required>{{ old('sujet_devoir', $devoir->sujet_devoir) }}</textarea>
            @error('sujet_devoir')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="cours_id">Cours</label>
            <select name="cours_id" id="cours_id" class="form-control @error('cours_id') is-invalid @enderror" required>
                <option value="">Sélectionnez un cours</option>
                @foreach($cours as $c)
                    <option value="{{ $c->id }}" {{ old('cours_id', $devoir->cours_id) == $c->id ? 'selected' : '' }}>{{ $c->nom }}</option>
                @endforeach
            </select>
            @error('cours_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="semestre_id">Semestre</label>
            <select name="semestre_id" id="semestre_id" class="form-control @error('semestre_id') is-invalid @enderror" required>
                <option value="">Sélectionnez un semestre</option>
                @foreach($semestres as $semestre)
                    <option value="{{ $semestre->id }}" {{ old('semestre_id', $devoir->semestre_id) == $semestre->id ? 'selected' : '' }}>{{ $semestre->nom }}</option>
                @endforeach
            </select>
            @error('semestre_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="annee_academique_id">Année Académique</label>
            <select name="annee_academique_id" id="annee_academique_id" class="form-control @error('annee_academique_id') is-invalid @enderror" required>
                <option value="">Sélectionnez une année académique</option>
                @foreach($anneesAcademiques as $annee)
                    <option value="{{ $annee->id }}" {{ old('annee_academique_id', $devoir->annee_academique_id) == $annee->id ? 'selected' : '' }}>{{ $annee->nom }}</option>
                @endforeach
            </select>
            @error('annee_academique_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="fichier_sujet">Fichier du sujet (PDF, DOC, DOCX - Max 5MB)</label>
            @if($devoir->fichier_sujet)
                <p>Fichier actuel : {{ $devoir->fichier_sujet }}</p>
            @endif
            <input type="file" name="fichier_sujet" id="fichier_sujet" class="form-control @error('fichier_sujet') is-invalid @enderror" accept=".pdf,.doc,.docx">
            @error('fichier_sujet')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary mt-3">Modifier le devoir</button>
    </form>
</div>
@endsection