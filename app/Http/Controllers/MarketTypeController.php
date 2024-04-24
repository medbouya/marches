<?php

namespace App\Http\Controllers;

use App\Models\MarketType;
use Illuminate\Http\Request;

class MarketTypeController extends Controller
{
    public function index()
    {
        $marketTypes = MarketType::all();
        return view('market_types.index', compact('marketTypes'));
    }

    public function create()
    {
        return view('market_types.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:market_types',
            'minimum_threshold' => 'required|integer',
        ]);

        MarketType::create($request->all());

        return redirect()->route('market-types.index')->with('success', 'Type de marché créé avec succès');
    }

    public function edit($id)
    {
        $marketType = MarketType::findOrFail($id);
        return view('market_types.edit', compact('marketType'));
    }

    public function update(Request $request, MarketType $marketType)
    {
        $request->validate([
            'name' => 'required|string|unique:market_types,name,' . $marketType->id,
            'minimum_threshold' => 'required|integer',
        ]);

        $marketType->update($request->all());

        return redirect()->route('market-types.index')->with('success', 'Type de marché mis à jour avec succès');
    }

    public function destroy(MarketType $marketType)
    {
        $marketType->delete();

        return redirect()->route('market-types.index')->with('success', 'Type de marché supprimé avec succès.');
    }
}
