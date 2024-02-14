<?php

namespace App\Http\Controllers;

use App\Models\AuditSetting;
use App\Models\Market;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch the minimum amount from AuditSettings
        $auditSetting = AuditSetting::first();

        // If no AuditSetting is found, you can handle it accordingly
        if (!$auditSetting) {
            // Handle the case where there is no AuditSetting
            // You can return an empty array or redirect to another page
            return view('dashboard.index', ['markets' => [], 'minimumAmount' => null, 'marketTypes' => []]);
        }

        // Fetch the minimum amount from the current AuditSetting
        $minimumAmount = $auditSetting->minimum_amount_to_audit;
        $auditionPercentage = $auditSetting->audition_percentage;

        $marketsCount = Market::get(); // Adjust according to your specific requirements

        // Calculate the total count and then the number of markets based on audition_percentage
        $totalMarketCount = $marketsCount->count();
        $auditedMarketCount = ceil(($auditionPercentage / 100.0) * $totalMarketCount);
        $minimumAmount = $auditSetting->minimum_amount_to_audit;
        $thresholdAudition = $auditSetting->threshold_exclusion;

        // Categorize markets
        $marketsAboveThreshold = $marketsCount->where('amount', '>', $minimumAmount)->count();
        $marketsBelowThreshold = $marketsCount->where('amount', '<', $thresholdAudition)->count();
        $marketsIntermediate = $marketsCount->whereBetween('amount', [$thresholdAudition, $minimumAmount])->count();

        // Prepare Pie Chart data
        $pieChartData = [$marketsAboveThreshold, $marketsBelowThreshold, $marketsIntermediate];
        $pieChartLabels = ['Marchés au-dessus du seuil', 'Marchés sous le seuil', 'Marchés intermédiaires'];
        $pieChartColors = ['#f56954', '#00a65a', '#f39c12']; // Example colors

        $marketsByModePassation = Market::join('mode_passations', 'markets.passation_mode', '=', 'mode_passations.id')
                        ->selectRaw('mode_passations.name as modePassationName, SUM(markets.amount) as totalAmount')
                        ->groupBy('mode_passations.name')
                        ->get();
        $barChartLabels = $marketsByModePassation->pluck('modePassationName');
        $barChartData = $marketsByModePassation->pluck('totalAmount');

        $marketsByAttributaire = Market::join('attributaires', 'markets.attributaire_id', '=', 'attributaires.id')
                        ->selectRaw('attributaires.name as attributaireName, COUNT(*) as marketCount')
                        ->groupBy('attributaires.name')
                        ->orderBy('marketCount', 'DESC')
                        ->take(5) // Taking top 5 for demonstration
                        ->get();

        $stackedBarChartLabels = $marketsByAttributaire->pluck('attributaireName');
        $stackedBarChartData = $marketsByAttributaire->pluck('marketCount');

        // Fetch markets with amount greater than or equal to the minimum amount for the specified year
        $markets = Market::where('amount', '>=', $minimumAmount)
            ->where('year', $auditSetting->year)
            ->get();
        
        $marketsCount = Market::all()->map(function($markets) {
            return $markets->count();
        });

        // Fetch market types from the current AuditSetting
        $marketTypes = $auditSetting->marketTypes;

        // Aggregating market data by ModePassation without the specified conditions
        $modePassationCounts = Market::with('modePassation') // Ensure 'modePassation' is your relation name
        ->get()
        ->groupBy('modePassation.name')
        ->map(function ($group) {
            return $group->count();
        });

        // Capitalize the labels
        $modePassationLabels = $modePassationCounts->keys()->map(function ($label) {
            return ucwords($label); // Capitalizes the first letter of each word
            // or use ucfirst($label) to capitalize the first letter of the first word
        })->all();
        $modePassationData = $modePassationCounts->values()->all();

        // Generate random colors
        $backgroundColors = collect($modePassationData)->map(function () {
            return '#' . substr(md5(rand()), 0, 6); // Generates a hex color
        })->all();

        return view('dashboard.index', compact('markets',
        'minimumAmount', 'marketTypes', 'auditSetting',
        'modePassationCounts', 'modePassationLabels', 'modePassationData',
        'backgroundColors', 'auditedMarketCount', 'totalMarketCount',
        'pieChartData', 'pieChartLabels', 'pieChartColors',
        'barChartLabels', 'barChartData', 'stackedBarChartLabels',
        'stackedBarChartData'));
    }

}
