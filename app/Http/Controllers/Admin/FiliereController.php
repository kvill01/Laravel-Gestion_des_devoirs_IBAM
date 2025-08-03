<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Filiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FiliereController extends Controller
{
    /**
     * Afficher la liste des filières
     */
    public function index()
    {
        $filieres = Filiere::orderBy('code')->get();
        return view('admin.filieres.index', compact('filieres'));
    }

    /**
     * Afficher le formulaire de création d'une filière
     */
    public function create()
    {
        return view('admin.filieres.create');
    }

    /**
     * Enregistrer une nouvelle filière
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:10|unique:filieres,code',
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Filiere::create([
            'code' => strtoupper($request->code),
            'nom' => $request->nom,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.filieres.index')
            ->with('success', 'Filière créée avec succès');
    }

    /**
     * Afficher le formulaire d'édition d'une filière
     */
    public function edit(Filiere $filiere)
    {
        return view('admin.filieres.edit', compact('filiere'));
    }

    /**
     * Mettre à jour une filière
     */
    public function update(Request $request, Filiere $filiere)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:10|unique:filieres,code,' . $filiere->id,
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $filiere->update([
            'code' => strtoupper($request->code),
            'nom' => $request->nom,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.filieres.index')
            ->with('success', 'Filière mise à jour avec succès');
    }

    /**
     * Supprimer une filière
     */
    public function destroy(Filiere $filiere)
    {
        // Vérifier si la filière est utilisée par des cours
        if ($filiere->cours()->count() > 0) {
            return redirect()->route('admin.filieres.index')
                ->with('error', 'Impossible de supprimer cette filière car elle est associée à des cours');
        }

        $filiere->delete();

        return redirect()->route('admin.filieres.index')
            ->with('success', 'Filière supprimée avec succès');
    }
}
