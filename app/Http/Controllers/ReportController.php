<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\Market;
use App\Models\MarketType;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function generateReport()
    {
        // Fetching markets and related data
        $markets = Market::with(['autoriteContractante', 'cpmp', 'marketType', 'modePassation', 'secteur', 'attributaire'])
            ->get();

        // Example of calculating additional statistics, adapt as necessary
        $totalMarketValue = $markets->sum('budget');
        $marketsByType = MarketType::withCount('markets')->get();

        // You might have other calculations or aggregations to perform
        // Example: Number of audits per market type
        $auditCounts = Audit::selectRaw('count(*) as count, market_type_id')
            ->groupBy('market_type_id')
            ->pluck('count', 'market_type_id');

        // Load the view and pass data
        $pdf = PDF::loadView('reports.market_report', [
            'markets' => $markets,
            'totalMarketValue' => $totalMarketValue,
            'marketsByType' => $marketsByType,
            'auditCounts' => $auditCounts
        ]);

        // Return the generated PDF as a download
        return $pdf->download('market-report-2023.pdf');
    }
}
