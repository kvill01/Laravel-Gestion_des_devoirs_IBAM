<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Niveau;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NiveauController extends Controller
{
    /**
     * Afficher la liste des niveaux
     */
    public function index()
    {
        $niveaux = Niveau::orderBy('code')->get();
        return view('admin.niveaux.index', compact('niveaux'));
    }

    /**
     * Afficher le formulaire de création d'un niveau
     */
    public function create()
    {
        return view('admin.niveaux.create');
    }

    /**
     * Enregistrer un nouveau niveau
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:10|unique:niveaux,code',
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Niveau::create([
            'code' => strtoupper($request->code),
            'nom' => $request->nom,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.niveaux.index')
            ->with('success', 'Niveau créé avec succès');
    }

    /**
     * Afficher le formulaire d'édition d'un niveau
     */
    public function edit(Niveau $niveau)
    {
        return view('admin.niveaux.edit', compact('niveau'));
    }

    /**
     * Mettre à jour un niveau
     */
    public function update(Request $request, Niveau $niveau)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:10|unique:niveaux,code,' . $niveau->id,
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $niveau->update([
            'code' => strtoupper($request->code),
            'nom' => $request->nom,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.niveaux.index')
            ->with('success', 'Niveau mis à jour avec succès');
    }

    /**
     * Supprimer un niveau
     */
    public function destroy(Niveau $niveau)
    {
        // Vérifier si le niveau est utilisé par des cours
        if ($niveau->cours()->count() > 0) {
            return redirect()->route('admin.niveaux.index')
                ->with('error', 'Impossible de supprimer ce niveau car il est associé à des cours');
        }

        $niveau->delete();

        return redirect()->route('admin.niveaux.index')
            ->with('success', 'Niveau supprimé avec succès');
    }
}
