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

        // Fetch markets with amount greater than or equal to the minimum amount for the specified year
        $markets = Market::where('amount', '>=', $minimumAmount)
            ->where('year', $auditSetting->year)
            ->get();

        // Fetch market types from the current AuditSetting
        $marketTypes = $auditSetting->marketTypes;

        return view('dashboard.index', compact('markets', 'minimumAmount', 'marketTypes', 'auditSetting'));
    }

}
