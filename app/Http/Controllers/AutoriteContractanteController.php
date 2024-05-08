<?php

namespace App\Http\Controllers;

use App\Models\AutoriteContractante;
use App\Models\MarketType;
use App\Models\Secteur;
use Illuminate\Http\Request;

class AutoriteContractanteController extends Controller
{
    public function index()
    {
        $autoriteContractantes = AutoriteContractante::orderBy('name')->paginate(10);
        return view('autorite-contractantes.index', compact('autoriteContractantes'));
    }

    public function create()
    {
        $marketTypes = MarketType::all();
        $secteurs = Secteur::all();
        return view('autorite-contractantes.create', compact('marketTypes', 'secteurs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required', 'address' => 'nullable', 'contact_person' => 'nullable', 'is_exempted' => 'nullable|boolean',
            'market_types' => 'array', // This assumes a multiple select or similar input
            'market_types.*' => 'exists:market_types,id', // Validating each market type ID
            'secteurs' => 'array', // This assumes a multiple select or similar input
            'secteurs.*' => 'exists:secteurs,id', // Validating each secteur ID
        ]);

        $autoriteContractante = AutoriteContractante::create($request->only(['name', 'address', 'contact_person', 'is_exempted']));

        // Attach market types using sync
        $autoriteContractante->marketTypes()->sync($request->input('market_types', []));

        return redirect()->route('autorite-contractantes.index')->with('success', 'Autorité contractante ajoutée avec succès.');
    }

    public function edit(AutoriteContractante $autoriteContractante)
    {
        $marketTypes = MarketType::all();
        $secteurs = Secteur::all();
        return view('autorite-contractantes.edit', compact('autoriteContractante', 'marketTypes', 'secteurs'));
    }

    public function update(Request $request, AutoriteContractante $autoriteContractante)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'nullable',
            'contact_person' => 'nullable',
            'is_exempted' => 'nullable|boolean',
            'market_types' => 'array',
            'market_types.*' => 'exists:market_types,id',
            'secteurs' => 'array',
            'secteurs.*' => 'exists:secteurs,id',
        ]);

        $autoriteContractante->update($request->only(['name', 'address', 'contact_person', 'is_exempted']));

        // Update market types using sync
        $autoriteContractante->marketTypes()->sync($request->input('market_types', []));

        return redirect()->route('autorite-contractantes.index')->with('success', 'Autorité contractante modifiée avec succès.');
    }

    public function destroy(AutoriteContractante $autoriteContractante)
    {
        $autoriteContractante->delete();
        $autoriteContractante->marketTypes()->detach(); // Detach all market types on delete

        return redirect()->route('autorite-contractantes.index')->with('success', 'Autorité contractante supprimée avec succès.');
    }
}
