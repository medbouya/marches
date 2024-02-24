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
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Request as FacadesRequest;
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

    public function marketsToAuditSummary()
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

        return view('markets.marketsToAuditSummary', compact('marketsAboveMinimumCount',
                                                        'modePassationCounts'));
    }

    public function getFilteredMarkets($exportType = null)
    {
        $auditSetting = AuditSetting::firstOrFail();
        $minimumAmount = $auditSetting->minimum_amount_to_audit;
        $thresholdExclusion = $auditSetting->threshold_exclusion;
        $auditionPercentage = $auditSetting->audition_percentage;

        $totalMarketsCount = Market::all()->count();
        $maxMarketsToAudit = ceil($totalMarketsCount * ($auditionPercentage / 100.0));

        // Step 1: Select all markets above the minimum amount
        $marketsAboveMinimum = Market::where('amount', '>=', $minimumAmount)->get();

        // Initialize collection for markets to be audited
        $marketsToAudit = collect($marketsAboveMinimum);

        // Step 2: Process markets between thresholdExclusion and minimumAmount by ModePassation rank
        $modePassations = ModePassation::orderBy('rank')->get();

        $count1 = 0;
        foreach($marketsToAudit as $market) {
            if ($market->modePassation->id == 2)
                $count1++;
        }
        
        foreach ($modePassations as $modePassation) {
            $eligibleMarkets = Market::where('passation_mode', $modePassation->id)
                                     ->whereBetween('amount', [$thresholdExclusion, $minimumAmount])
                                     ->get();
        
            $percentageToSelect = $modePassation->percentage / 100;
            $countToSelect = ceil($eligibleMarkets->count() * $percentageToSelect);
        
            // Calculate space left before adding new markets
            $spaceLeftBeforeAdding = $maxMarketsToAudit - $marketsToAudit->count();
        
            $finalCountToSelect = min($spaceLeftBeforeAdding, $countToSelect);
        
            if ($finalCountToSelect <= 0) break; // If no space left, break the loop
        
            $selectedMarkets = $eligibleMarkets->random($finalCountToSelect);
        
            $marketsToAudit = $marketsToAudit->merge($selectedMarkets);
        
        }

        $count2 = 0;
        foreach($marketsToAudit as $market) {
            if ($market->modePassation->id == 2)
                $count1++;
        }
        
        // Step 1: Fetch all eligible markets not included in the $marketsToAudit
        $eligibleMarketIdsNotSelected = Market::whereBetween('amount', [$thresholdExclusion, $minimumAmount])
                                                ->whereNotIn('id', $marketsToAudit->pluck('id'))
                                                ->pluck('id');

        // Step 2: Identify less important ModePassation markets with a connection to the selected markets
        $lessImportantMarkets = Market::whereIn('id', $eligibleMarketIdsNotSelected)
                                            ->whereHas('modePassation', function ($query) {
                                            // Assuming ModePassation has a 'rank' and you're looking for lower importance
                                            // Adjust the logic here based on how you define "less important"
                                            $query->orderBy('rank', 'desc');
                                            })
                                            ->whereHas('attributaire', function ($query) use ($marketsToAudit) {
                                            // Filter to include markets whose attributaire is also in the selected markets
                                            $attributaireIds = $marketsToAudit->pluck('attributaire.id')->unique();
                                            $query->whereIn('id', $attributaireIds);
                                            })
                                            ->paginate(10);

        // Ensure unique markets in case of any overlap
        $marketsToAudit = $marketsToAudit->unique('id');
        $count = 0;
        foreach($marketsToAudit as $market) {
            if ($market->modePassation->id == 2)
                $count++;
        }

        $allMarketIds = $marketsToAudit->pluck('id')->toArray();

        $filteredMarkets = Market::whereIn('id', $allMarketIds)
                                            ->orderBy('amount', 'desc')
                                            ->get();
        // Excel export
        if ($exportType === 'excel') {
            return Excel::download(new class($filteredMarkets) implements FromCollection, WithHeadings {
                private $data;
    
                public function __construct($data)
                {
                    $this->data = $data;
                }
    
                public function collection()
                {
                    $this->data->load(['modePassation', 
                                        'attributaire',
                                        'marketType',
                                        'secteur',
                                        'cpmp',
                                        'autoriteContractante'
                                    ]);

                    // Transform the data to include related model names instead of IDs
                    $transformed = $this->data->map(function ($item) {
                        return [
                            'Numéro' => $item->numero,
                            'Année' => $item->year,
                            'Objet' => $item->title,
                            'CPMP' => strtoupper($item->CPMP->name) ?? 'N/A',
                            'Autorité contractante' => strtoupper($item->autoriteContractante->name) ?? 'N/A',
                            //'Type de marché' => strtoupper($item->marketType->name) ?? 'N/A',
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
                            "Mode de passation", "Secteur",
                            "Montant", "Financement", "Attributaire",
                            "Date de signature", "Date de notification",
                            "Date de publication", "Délai d'exécution"];
                }
            }, 'markets.xlsx');
        }

        // PDF export
        if ($exportType === 'pdf') {
            $pdf = PDF::loadView('markets.pdf', compact('filteredMarkets'));
            return $pdf->download('markets.pdf');
        }

        // Assuming $marketsToAudit is your collection of markets to be paginated
        $perPage = 10; // The number of items per page
        $page = FacadesRequest::get('page', 1); // Use Laravel's Request facade to get the current page or default to 1
        $offset = ($page - 1) * $perPage; // Calculate the offset
        $filteredMarkets = $marketsToAudit;

        $marketsToAuditIds = $marketsToAudit->pluck('id');
        $marketsToAudit = Market::with('modePassation')
                        ->whereIn('id', $marketsToAuditIds)
                        ->get()
                        ->sortBy(function ($market) {
                            return $market->modePassation->rank;
                        });

        $itemsForCurrentPage = $marketsToAudit->slice($offset, $perPage)->values();

        // Create the paginator
        $paginatedMarkets = new LengthAwarePaginator(
            $itemsForCurrentPage, // The items for the current page
            $marketsToAudit->count(), // Total items in the collection
            $perPage, // Items per page
            $page, // Current page
            [
                'path' => FacadesRequest::url(), // The path of the route
                'query' => FacadesRequest::query(), // Query parameters
            ]
        );

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

        return view('markets.marketsToAudit', compact('paginatedMarkets', 
                                                        'lessImportantMarkets',
                                                        'marketsAboveMinimumCount', 
                                                        'modePassationCounts'));
    }

    // Define a method to fetch markets from less important ModePassation ranks based on conditions
    private function getLessImportantMarkets($selectedMarketIds)
    {
        // Example logic, adjust as necessary based on exact requirements
        $selectedMarkets = Market::whereIn('id', $selectedMarketIds)->get();
        $selectedAutoriteContractanteIds = $selectedMarkets->pluck('authority_contracting');

        $lessImportantMarketIds = Market::whereNotIn('id', $selectedMarketIds)
                                        ->whereIn('authority_contracting', $selectedAutoriteContractanteIds)
                                        ->whereHas('modePassation', function ($query) {
                                            $query->orderBy('rank', 'desc');
                                        })
                                        ->pluck('id');

        return $lessImportantMarketIds;
    }
}
