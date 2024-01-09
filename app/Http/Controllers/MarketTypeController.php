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
        $request->validate([
            'name' => 'required|string|unique:market_types'
        ]);

        MarketType::create($request->all());

        return redirect()->route('market-types.index')->with('success', 'Market type created successfully');
    }

    public function update(Request $request, MarketType $marketType)
    {
        $request->validate([
            'name' => 'required|string|unique:market_types,name,' . $marketType->id,
            // Add more validation rules as needed
        ]);

        $marketType->update($request->all());

        return redirect()->route('market-types.index')->with('success', 'Market type updated successfully');
    }

    public function destroy(MarketType $marketType)
    {
        $marketType->delete();

        return redirect()->route('market-types.index')->with('success', 'Market type deleted successfully');
    }
}
