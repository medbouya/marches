<?php

namespace App\Http\Controllers;

use App\Models\Attributaire;
use App\Models\AuditSetting;
use App\Models\AutoriteContractante;
use App\Models\CPMP;
use App\Models\Market;
use App\Models\MarketType;
use App\Models\ModePassation;
use App\Models\Secteur;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;

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

    public function getFilteredMarkets($exportType = null)
    {
        $auditSetting = AuditSetting::firstOrFail();
        $minimumAmount = $auditSetting->minimum_amount_to_audit;
        $thresholdExclusion = $auditSetting->threshold_exclusion;

        // Assuming $eligibleModePassationIds logic needs to be revised for clarity and correct functionality
        $eligibleModePassationIds = ModePassation::get()->flatMap(function ($modePassation) use ($minimumAmount, $thresholdExclusion) {
            // Calculate the number of markets to include for each ModePassation
            $marketsCount = Market::where('passation_mode', $modePassation->id)
                                ->where('amount', '>', $thresholdExclusion)
                                ->where('amount', '<', $minimumAmount)
                                ->count();
            $percentage = $modePassation->percentage ?? 100;
            $eligibleCount = ceil($marketsCount * ($percentage / 100.0));

            // Return the mode_passation_id for the eligible number of markets
            return Market::where('passation_mode', $modePassation->id)
                        ->where('amount', '>', $thresholdExclusion)
                        ->where('amount', '<', $minimumAmount)
                        ->select('id') // Just fetch the IDs to minimize data fetched
                        ->take($eligibleCount) // Take only as many as eligible
                        ->pluck('id'); // Return only IDs to avoid fetching full models
        });

        // Fetch markets directly eligible above the minimum amount
        $marketsAboveMinimum = Market::where('amount', '>=', $minimumAmount)->pluck('id');

        // Combine IDs for fetching
        $allMarketIds = $marketsAboveMinimum->merge($eligibleModePassationIds)->unique();

        // Fetch paginated markets using the combined IDs
        $filteredMarkets = Market::whereIn('id', $allMarketIds)
                                            ->orderBy('amount', 'desc')
                                            ->paginate(10);

        // Excel export
        if ($exportType === 'excel') {
            $filteredMarkets = Market::whereIn('id', $allMarketIds)
                                            ->orderBy('amount', 'desc')
                                            ->get();
            return Excel::download(new class($filteredMarkets) implements FromCollection, WithHeadings {
                private $data;
    
                public function __construct($data)
                {
                    $this->data = $data;
                }
    
                public function collection()
                {
                    $this->data->load(['modePassation', 'attributaire']);

                    // Transform the data to include related model names instead of IDs
                    $transformed = $this->data->map(function ($item) {
                        return [
                            'Numéro' => $item->numero,
                            'Année' => $item->year,
                            'Objet' => $item->title,
                            'CPMP' => strtoupper($item->CPMP->name) ?? 'N/A',
                            'Autorité contractante' => strtoupper($item->autoriteContractante->name) ?? 'N/A',
                            'Type de marché' => strtoupper($item->marketType->name) ?? 'N/A',
                            'Mode de Passation' => strtoupper($item->modePassation->name) ?? 'N/A',
                            'Secteur' => strtoupper($item->secteur->name) ?? 'N/A',
                            'Montant' => $item->amount,
                            'Financement' => $item->financement ?? 'N/A',
                            'Attributaire' => strtoupper($item->attributaire->name) ?? 'N/A',
                            'Date de signature' => $item->date_signature,
                            'Date de notification' => $item->date_notification,
                            'Date de publication' => $item->date_publication,
                            'Délai d\'exécution' => $item->delai_execution,

                        ];
                    });

                    return $transformed;
                }
    
                public function headings(): array
                {
                    return ["Numéro", "Année", "Objet", "CPMP", "Autorité contractante",
                            "Type de marché", "Mode de passation", "Secteur",
                            "Montant", "Financement", "Attributaire",
                            "Date de signature", "Date de notification",
                            "Date de publication", "Délai d'exécution"];
                }
            }, 'markets.xlsx');
        }

        // PDF export
        if ($exportType === 'pdf') {
            $filteredMarkets = Market::whereIn('id', $allMarketIds)
                                     ->orderBy('amount', 'desc')
                                     ->get(); // Get all, not paginated
        
            $pdf = PDF::loadView('markets.pdf', compact('filteredMarkets'));
            return $pdf->download('markets.pdf');
        }

        $filteredMarketsWithRelations = Market::whereIn('id', $allMarketIds)
                                           ->with(['modePassation', 'attributaire']) // Adjust based on needed relations
                                           ->orderBy('amount', 'desc')
                                           ->get();

        // Calculate statistics based on $filteredMarketsWithRelations
        $marketsAboveMinimumCount = $filteredMarketsWithRelations->where('amount', '>=', $minimumAmount)->count();
        
        // Calculate the number of markets for each ModePassation within the filtered results
        $modePassationCounts = $filteredMarketsWithRelations->groupBy('modePassation.name')
                                                            ->map(function ($group) {
                                                                return count($group);
                                                            });

        return view('markets.marketsToAudit', compact('filteredMarkets', 
                                                        'marketsAboveMinimumCount', 
                                                        'modePassationCounts'));
    }
}
