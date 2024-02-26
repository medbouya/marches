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
        $markets = Market::with(
            'marketType',
            'modePassation',
            'autoriteContractante'
        )->paginate(10);
        return view('markets.index', compact('markets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $marketTypes = MarketType::all();
        $modePassations = ModePassation::all();
        $autoriteContractantes = AutoriteContractante::all();
        $cpmps = CPMP::all();
        $secteurs = Secteur::all();
        $attributaires = Attributaire::all();
        return view('markets.create', compact(
            'marketTypes',
            'modePassations',
            'autoriteContractantes',
            'cpmps',
            'secteurs',
            'attributaires'
        ));
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
            'market_type_id' => 'required|exists:market_types,id',
        ]);

        Market::create($request->all());

        return redirect()->route('markets.index')->with(
            'success',
            'Marché ajouté avec succès.'
        );
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
        $modePassations = ModePassation::all();
        $autoriteContractantes = AutoriteContractante::all();
        $cpmps = CPMP::all();
        $secteurs = Secteur::all();
        $attributaires = Attributaire::all();

        return view(
            'markets.edit',
            compact(
                'market',
                'marketTypes',
                'modePassations',
                'autoriteContractantes',
                'cpmps',
                'secteurs',
                'attributaires'
            )
        );
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
            'market_type_id' => 'required|exists:market_types,id',
            'passation_mode' => 'required|exists:mode_passations,id',
            'authority_contracting' => 'required|exists:autorite_contractantes,id',
            // Add other validation rules as needed
        ]);

        // Update market attributes
        $market->update($request->all());

        // Redirect or do anything else after updating...

        return redirect()->route('markets.index')->with(
            'success',
            'Marché mis à jour avec succès.'
        );
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

        return redirect()->route('markets.index')->with(
            'success',
            'Marché supprimé avec succès.'
        );
    }

    public function marketsToAuditSummary()
    {
        $auditSetting = AuditSetting::firstOrFail();
        $minimumAmount = $auditSetting->minimum_amount_to_audit;
        $thresholdExclusion = $auditSetting->threshold_exclusion;
        $auditionPercentage = $auditSetting->audition_percentage;

        $totalMarketsCount = Market::all()->count();
        $maxMarketsToAudit = ceil($totalMarketsCount * ($auditionPercentage / 100.0));

        $marketsAboveMinimum = Market::where('amount', '>=', $minimumAmount)->get();

        $modePassations = ModePassation::orderBy('rank')->get();
        $selectedMarkets = collect([]);

        foreach ($modePassations as $modePassation) {
            // Fetch markets eligible under each mode of passation within the specified amount range.
            $eligibleMarkets = Market::where('passation_mode', $modePassation->id)
                ->whereBetween('amount', [$thresholdExclusion, $minimumAmount])
                ->get();

            $percentageToSelect = $modePassation->percentage / 100;
            $countToSelect = ceil($eligibleMarkets->count() * $percentageToSelect);

            if ($countToSelect > 0) {
                $selectedMarkets = $selectedMarkets->merge($eligibleMarkets->random(min($countToSelect, $eligibleMarkets->count())));
            }
        }

        $spaceLeftForRandomSelection = $maxMarketsToAudit - $marketsAboveMinimum->count();

        if ($selectedMarkets->count() > $spaceLeftForRandomSelection) {
            $selectedMarkets = $selectedMarkets->random($spaceLeftForRandomSelection);
        }

        // Now merge the adjusted selectedMarkets with marketsAboveMinimum.
        $marketsToAudit = $selectedMarkets->merge($marketsAboveMinimum)->unique('id');

        $marketsAboveMinimumCount = $marketsToAudit->where(
            'amount',
            '>=',
            $minimumAmount
        )->count();

        // Calculate the number of markets for each ModePassation within the filtered results
        $modePassationCounts = $marketsToAudit->groupBy('modePassation.name')
                                                            ->map(function ($group) {
                                                                return count($group);
                                                            });

        return view('markets.marketsToAuditSummary', compact(
            'marketsAboveMinimumCount',
            'modePassationCounts'
        ));
    }

    public function getFilteredMarkets($exportType = null)
    {
        $auditSetting = AuditSetting::firstOrFail();
        $minimumAmount = $auditSetting->minimum_amount_to_audit;
        $thresholdExclusion = $auditSetting->threshold_exclusion;
        $auditionPercentage = $auditSetting->audition_percentage;

        $totalMarketsCount = Market::all()->count();
        $maxMarketsToAudit = ceil($totalMarketsCount * ($auditionPercentage / 100.0));

        $marketsAboveMinimum = Market::where('amount', '>=', $minimumAmount)->get();

        $modePassations = ModePassation::orderBy('rank')->get();
        $selectedMarkets = collect([]);

        foreach ($modePassations as $modePassation) {
            // Fetch markets eligible under each mode of passation within the specified amount range.
            $eligibleMarkets = Market::where('passation_mode', $modePassation->id)
            ->whereBetween('amount', [$thresholdExclusion, $minimumAmount])
            ->get();

            $percentageToSelect = $modePassation->percentage / 100;
            $countToSelect = ceil($eligibleMarkets->count() * $percentageToSelect);

            if ($countToSelect > 0) {
                $selectedMarkets = $selectedMarkets->merge($eligibleMarkets->random(min($countToSelect, $eligibleMarkets->count())));
            }
        }

        // Check if the total exceeds the max allowed for audit BEFORE merging with marketsAboveMinimum.
        $spaceLeftForRandomSelection = $maxMarketsToAudit - $marketsAboveMinimum->count();

        if ($selectedMarkets->count() > $spaceLeftForRandomSelection) {
            $selectedMarkets = $selectedMarkets->random($spaceLeftForRandomSelection);
        }

        // Now merge the adjusted selectedMarkets with marketsAboveMinimum.
        $marketsToAudit = $selectedMarkets->merge($marketsAboveMinimum)->unique('id');

        $eligibleMarketIdsNotSelected = Market::whereBetween(
            'amount',
            [$thresholdExclusion, $minimumAmount]
        )->whereNotIn('id', $marketsToAudit->pluck('id'))->pluck('id');

        $lessImportantMarkets = Market::whereIn('id', $eligibleMarketIdsNotSelected)
            ->whereHas('modePassation', function ($query) {
                                            $query->orderBy('rank', 'desc');
                                            })
            ->whereHas(
                'attributaire',
                function ($query) use ($marketsToAudit) {
                    $attributaireIds = $marketsToAudit->pluck('attributaire.id')->unique();
                    $query->whereIn('id', $attributaireIds);
                }
            )->paginate(10);

        $marketsToAudit = $marketsToAudit->unique('id');
        $count = 0;
        foreach($marketsToAudit as $market) {
            if ($market->modePassation->id == 2)
                $count++;
        }

        $allMarketIds = $marketsToAudit->pluck('id')->toArray();

        $filteredMarkets = Market::whereIn(
            'id',
            $allMarketIds
        )
        ->orderBy('amount', 'desc')
        ->get();

        // Excel export
        if ($exportType === 'excel') {
            return Excel::download(
                new class($filteredMarkets) implements FromCollection, WithHeadings
                {
                    private $data;

                    public function __construct($data)
                    {
                        $this->data = $data;
                    }

                    public function collection()
                    {
                        $this->data->load([
                            'modePassation',
                            'attributaire',
                            'marketType',
                            'secteur',
                            'cpmp',
                            'autoriteContractante'
                        ]);

                        $transformed = $this->data->map(function ($item) {
                            return [
                                'Numéro' => $item->numero,
                                'Année' => $item->year,
                                'Objet' => $item->title,
                                'CPMP' => strtoupper($item->CPMP->name) ?? 'N/A',
                                'Autorité contractante' => strtoupper(
                                    $item->autoriteContractante->name
                                ) ?? 'N/A',
                                //'Type de marché' => strtoupper($item->marketType->name) ?? 'N/A',
                                'Mode de Passation' => strtoupper(
                                    $item->modePassation->name
                                ) ?? 'N/A',
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

        $perPage = 10;
        $page = FacadesRequest::get('page', 1);
        $offset = ($page - 1) * $perPage;
        $filteredMarkets = $marketsToAudit;

        $marketsToAuditIds = $marketsToAudit->pluck('id');
        $marketsToAudit = Market::with('modePassation')
                        ->whereIn('id', $marketsToAuditIds)
                        ->get()
                        ->sortBy(function ($market) {
                            return $market->modePassation->rank;
                        });

        $itemsForCurrentPage = $marketsToAudit->slice($offset, $perPage)->values();

        $paginatedMarkets = new LengthAwarePaginator(
            $itemsForCurrentPage,
            $marketsToAudit->count(),
            $perPage,
            $page,
            [
                'path' => FacadesRequest::url(),
                'query' => FacadesRequest::query(),
            ]
        );

        $filteredMarketsWithRelations = Market::whereIn(
            'id',
            $allMarketIds
        )
        ->with(['modePassation', 'attributaire'])
        ->orderBy('amount', 'desc')
        ->get();

        // Calculate statistics based on $filteredMarketsWithRelations
        $marketsAboveMinimumCount = $filteredMarketsWithRelations->where(
            'amount',
            '>=',
            $minimumAmount
        )->count();
        
        $modePassationCounts = $filteredMarketsWithRelations->groupBy('modePassation.name')
                                                            ->map(function ($group) {
                                                                return count($group);
                                                            });

        return view('markets.marketsToAudit', compact('paginatedMarkets', 
                                                        'lessImportantMarkets',
                                                        'marketsAboveMinimumCount', 
                                                        'modePassationCounts'));
    }
}
