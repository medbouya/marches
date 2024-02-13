<?php

namespace App\Http\Controllers;

use App\Models\AutoriteContractante;
use Illuminate\Http\Request;

class AutoriteContractanteController extends Controller
{
    public function index()
    {
        $autoriteContractantes = AutoriteContractante::paginate(10);
        return view('autorite-contractantes.index', compact('autoriteContractantes'));
    }

    public function create()
    {
        return view('autorite-contractantes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'nullable',
            'contact_person' => 'nullable',
        ]);

        AutoriteContractante::create($request->all());

        return redirect()->route('autorite-contractantes.index')->with('success', 'Autorité contractante ajoutée avec succès.');
    }

    public function edit(AutoriteContractante $autoriteContractante)
    {
        return view('autorite-contractantes.edit', compact('autoriteContractante'));
    }

    public function update(Request $request, AutoriteContractante $autoriteContractante)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'nullable',
            'contact_person' => 'nullable',
        ]);

        $autoriteContractante->update($request->all());

        return redirect()->route('autorite-contractantes.index')->with('success', 'Autorité contractante modifiée avec succès.');
    }

    public function destroy(AutoriteContractante $autoriteContractante)
    {
        $autoriteContractante->delete();

        return redirect()->route('autorite-contractantes.index')->with('success', 'Autorité contractante supprimée avec succès.');
    }
}
