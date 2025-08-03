<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Salle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SalleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $salles = Salle::orderBy('nom')->paginate(10);
        return view('admin.salles.index', compact('salles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.salles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255|unique:salles',
            'capacite' => 'required|integer|min:1',
            'type' => 'required|string|max:255',
            'localisation' => 'required|string|max:255',
            'disponible' => 'boolean',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $salle = Salle::create([
            'nom' => $request->nom,
            'capacite' => $request->capacite,
            'type' => $request->type,
            'localisation' => $request->localisation,
            'disponible' => $request->has('disponible') ? true : false,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.salles.index')
            ->with('success', 'Salle créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $salle = Salle::findOrFail($id);
        return view('admin.salles.show', compact('salle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $salle = Salle::findOrFail($id);
        return view('admin.salles.edit', compact('salle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $salle = Salle::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255|unique:salles,nom,' . $id,
            'capacite' => 'required|integer|min:1',
            'type' => 'required|string|max:255',
            'localisation' => 'required|string|max:255',
            'disponible' => 'boolean',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $salle->update([
            'nom' => $request->nom,
            'capacite' => $request->capacite,
            'type' => $request->type,
            'localisation' => $request->localisation,
            'disponible' => $request->has('disponible') ? true : false,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.salles.index')
            ->with('success', 'Salle mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $salle = Salle::findOrFail($id);
        $salle->delete();

        return redirect()->route('admin.salles.index')
            ->with('success', 'Salle supprimée avec succès.');
    }
}
