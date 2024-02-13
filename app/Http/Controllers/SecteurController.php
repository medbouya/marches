<?php

namespace App\Http\Controllers;

use App\Models\Secteur;
use Illuminate\Http\Request;

class SecteurController extends Controller
{
    public function index()
    {
        $secteurs = Secteur::paginate(10);
        return view('secteurs.index', compact('secteurs'));
    }

    public function create()
    {
        return view('secteurs.create');
    }

    public function store(Request $request)
    {
        Secteur::create($request->all());
        return redirect()->route('secteurs.index')->with('success', 'Secteur created successfully.');
    }

    public function show(Secteur $secteur)
    {
        return view('secteurs.show', compact('secteur'));
    }

    public function edit(Secteur $secteur)
    {
        return view('secteurs.edit', compact('secteur'));
    }

    public function update(Request $request, Secteur $secteur)
    {
        $secteur->update($request->all());
        return redirect()->route('secteurs.index')->with('success', 'Secteur updated successfully.');
    }

    public function destroy(Secteur $secteur)
    {
        $secteur->delete();
        return redirect()->route('secteurs.index')->with('success', 'Secteur deleted successfully.');
    }
}
