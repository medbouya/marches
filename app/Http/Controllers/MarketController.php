<?php

namespace App\Http\Controllers;

use App\Models\Attributaire;
use App\Models\AutoriteContractante;
use App\Models\CPMP;
use App\Models\Market;
use App\Models\MarketType;
use App\Models\ModePassation;
use App\Models\Secteur;
use Illuminate\Http\Request;

class MarketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $markets = Market::with('marketType', 'modePassation', 'autoriteContractante')->paginate(10);
        return view('markets.index', compact('markets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $marketTypes = MarketType::all(); // Fetch market types from the database
        $modePassations = ModePassation::all(); // Fetch mode passations
        $autoriteContractantes = AutoriteContractante::all(); // Fetch autorite contractantes
        $cpmps = CPMP::all();
        $secteurs = Secteur::all();
        $attributaires = Attributaire::all();
        return view('markets.create', compact('marketTypes', 'modePassations', 'autoriteContractantes', 'cpmps', 'secteurs', 'attributaires'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate and store data
        $request->validate([
            'title' => 'required|string',
            'year' => 'required|digits:4|integer',
            'amount' => 'required|numeric',
            'passation_mode' => 'required|exists:mode_passations,id',
            'authority_contracting' => 'required|exists:autorite_contractantes,id',
            'market_type_id' => 'required|exists:market_types,id', // Adjust the validation rule based on your needs
            // Add other validation rules as needed
        ]);

        // Create a new market
        Market::create($request->all());

        // Redirect or do anything else after saving...

        return redirect()->route('markets.index')->with('success', 'Marché ajouté avec succès.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Market $market)
    {
        return view('markets.show', compact('market'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $market = Market::findOrFail($id);
        $marketTypes = MarketType::all();
        $modePassations = ModePassation::all(); // Fetch mode passations
        $autoriteContractantes = AutoriteContractante::all(); // Fetch autorite contractantes
        $cpmps = CPMP::all();
        $secteurs = Secteur::all();
        $attributaires = Attributaire::all();

        return view('markets.edit', 
            compact('market', 'marketTypes', 'modePassations', 'autoriteContractantes', 'cpmps', 'secteurs', 'attributaires'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $market = Market::findOrFail($id);
        // Validate and update the market
        $request->validate([
            'title' => 'required|string',
            'year' => 'required|digits:4|integer',
            'amount' => 'required|numeric',
            'authority_contracting' => 'required|string',
            'passation_mode' => 'required|string',
            'market_type_id' => 'required|exists:market_types,id', // Adjust the validation rule based on your needs
            'passation_mode' => 'required|exists:mode_passations,id',
            'authority_contracting' => 'required|exists:autorite_contractantes,id',
            // Add other validation rules as needed
        ]);

        // Update market attributes
        $market->update($request->all());

        // Redirect or do anything else after updating...

        return redirect()->route('markets.index')->with('success', 'Marché mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $market = Market::findOrFail($id);
        // Delete the market
        $market->delete();

        // Redirect or do anything else after deleting...

        return redirect()->route('markets.index')->with('success', 'Marché supprimé avec succès.');
    }
}
