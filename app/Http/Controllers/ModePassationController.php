<?php

namespace App\Http\Controllers;

use App\Models\ModePassation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ModePassationController extends Controller
{
    public function index()
    {
        $modePassations = ModePassation::all();
        return view('mode-passations.index', compact('modePassations'));
    }

    public function show(ModePassation $modePassation)
    {
        return view('mode-passations.show', compact('modePassation'));
    }

    public function create()
    {
        return view('mode-passations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
        ]);

        ModePassation::create($request->all());

        return redirect()->route('mode-passations.index')->with('success', 'Mode de passation ajouté avec succès.');
    }

    public function edit(ModePassation $modePassation)
    {
        return view('mode-passations.edit', compact('modePassation'));
    }

    public function update(Request $request, ModePassation $modePassation)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
        ]);

        $modePassation->update($request->all());

        return redirect()->route('mode-passations.index')->with('success', 'Mode de passation modifié avec succès.');
    }

    public function destroy(ModePassation $modePassation)
    {
        $modePassation->delete();

        return redirect()->route('mode-passations.index')->with('success', 'Mode de passation supprimé avec succès.');
    }

    // Function updating the ModePassation object rank
    public function updateRank(Request $request)
    {
        $ranks = $request->input('ranks', []);
        foreach ($ranks as $id => $rank) {
            ModePassation::where('id', $id)->update(['rank' => $rank]);
        }

        return redirect()->route('mode-passations.index')->with('success', 'Rang mis à jour.');
    }
}
