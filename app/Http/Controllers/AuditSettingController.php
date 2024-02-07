<?php

namespace App\Http\Controllers;

use App\Models\AuditSetting;
use App\Models\MarketType;
use App\Models\ModePassation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuditSettingController extends Controller
{
    public function index()
    {
        // Retrieve existing AuditSetting entry
        $auditSetting = AuditSetting::first();
        $auditSettings = AuditSetting::all();
        $modesPassation = ModePassation::all();

        return view('audit_settings.index', compact('auditSetting', 'auditSettings', 'modesPassation'));
    }

    public function create()
    {
        $marketTypes = MarketType::all();
        $modePassations = ModePassation::all();
        return view('audit_settings.create', compact('marketTypes', 'modePassations'));
    }

    public function store(Request $request)
    {
        // Validate and store data
        $request->validate([
            'year' => 'required|digits:4|integer',
            'minimum_amount_to_audit' => 'required|numeric',
            'market_type_id' => 'required|array',
            'market_type_id.*' => 'exists:market_types,id',
            // Add other validation rules as needed
        ], [
            'year.required' => 'The year field is required.',
            'year.digits' => 'The year must be a four-digit number.',
            'year.integer' => 'The year must be an integer.',
            'minimum_amount_to_audit.required' => 'The minimum amount to audit field is required.',
            'minimum_amount_to_audit.numeric' => 'The minimum amount to audit must be a numeric value.',
            'market_type_id.required' => 'Please select at least one market type.',
            'market_type_id.*.exists' => 'Invalid market type selected.',
            // Add other custom error messages as needed
        ]);

        // Check if an entry already exists for the given year
        $existingAuditSetting = AuditSetting::where('year', $request->input('year'))->first();

        if ($existingAuditSetting) {
            // If an entry already exists, update its values
            $existingAuditSetting->update([
                'minimum_amount_to_audit' => $request->input('minimum_amount_to_audit'),
                // Update other fields as needed
            ]);

            // Attach market types to the existing audit setting
            $existingAuditSetting->marketTypes()->sync($request->input('market_type_id'));
        } else {
            // If no entry exists, create a new one
            $newAuditSetting = AuditSetting::create([
                'year' => $request->input('year'),
                'minimum_amount_to_audit' => $request->input('minimum_amount_to_audit'),
                // Other fields...
            ]);

            // Attach market types to the new audit setting
            $newAuditSetting->marketTypes()->attach($request->input('market_type_id'));
        }

        // Redirect or do anything else after saving...

        return redirect()->route('audit-settings.index')->with('success', 'Audit setting created/updated successfully');
    }

    public function edit($id)
    {
        // Retrieve the auditSetting and marketTypes data from your database
        $auditSetting = AuditSetting::findOrFail($id);
        $modePassations = ModePassation::all();
        $marketTypes = MarketType::all(); // Adjust this query based on your data structure

        // Pass the data to the view
        return view('audit_settings.edit', compact('auditSetting', 'marketTypes', 'modePassations'));
    }

    public function update(Request $request, AuditSetting $auditSetting)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'year' => 'required|digits:4|integer',
            'minimum_amount_to_audit' => 'required|numeric',
            'market_type_id' => 'required|array',
            'market_type_id.*' => 'exists:market_types,id',
            // Add other validation rules as needed
        ], [
            'year.required' => 'The year field is required.',
            'year.digits' => 'The year must be a four-digit number.',
            'year.integer' => 'The year must be an integer.',
            'minimum_amount_to_audit.required' => 'The minimum amount to audit field is required.',
            'minimum_amount_to_audit.numeric' => 'The minimum amount to audit must be a numeric value.',
            'market_type_id.required' => 'Please select at least one market type.',
            'market_type_id.*.exists' => 'Invalid market type selected.',
            // Add other custom error messages as needed
        ]);

        // If validation fails, redirect back with errors
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Update the audit setting
        $auditSetting->update([
            'year' => $request->input('year'),
            'minimum_amount_to_audit' => $request->input('minimum_amount_to_audit'),
            // Update other fields as needed
        ]);

        // Attach market types to the audit setting
        $auditSetting->marketTypes()->sync($request->input('market_type_id'));

        // Redirect or do anything else after updating...
        return redirect()->route('audit-settings.index')->with('success', 'Audit setting updated successfully');
    }

    public function storeOrUpdate(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'year' => 'required|digits:4|integer',
            'minimum_amount_to_audit' => 'required|numeric',
            'threshold_exclusion' => 'required|numeric',
            'market_type_id' => 'required|array',
            'market_type_id.*' => 'exists:market_types,id',
            // Add other validation rules as needed
        ], [
            'year.required' => 'The year field is required.',
            'year.digits' => 'The year must be a four-digit number.',
            'year.integer' => 'The year must be an integer.',
            'minimum_amount_to_audit.required' => 'The minimum amount to audit field is required.',
            'minimum_amount_to_audit.numeric' => 'The minimum amount to audit must be a numeric value.',
            'threshold_exclusion.required' => 'The exclusion amount to audit field is required.',
            'threshold_exclusion.numeric' => 'The exclusion amount to audit must be a numeric value.',
            'market_type_id.required' => 'Please select at least one market type.',
            'market_type_id.*.exists' => 'Invalid market type selected.',
            // Add other custom error messages as needed
        ]);

        // If validation fails, redirect back with errors
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Retrieve existing AuditSetting entry if it exists; otherwise, create a new one
        $auditSetting = AuditSetting::firstOrNew(['year' => $request->input('year')]);

        // Update the fields of the AuditSetting instance
        $auditSetting->minimum_amount_to_audit = $request->input('minimum_amount_to_audit');
        // Update other fields as needed...

        // Retrieve percentages from user input
        $percentages = $request->input('percentages', []);

        // Calculate the sum of all percentages
        $sumPercentages = array_sum($percentages);

        // Check if the sum of all percentages exceeds 100
        if ($sumPercentages > 100) {
            return redirect()
                ->back()
                    ->withErrors(['percentages' => 'La somme de tous les pourcentages ne doit pas dépasser 100%'])
                        ->withInput();
        }

        // Save the AuditSetting instance
        $auditSetting->save();

        // Sync market types related to the AuditSetting
        $auditSetting->marketTypes()->sync($request->input('market_type_id'));

        // Update the percentages for each ModePassation entry
        foreach ($percentages as $modePassationId => $percentage) {
            $modePassation = ModePassation::findOrFail($modePassationId);
            $modePassation->percentage = $percentage;
            $modePassation->save();
        }
        // Redirect or do anything else after saving...
        return redirect()->route('audit-settings.index')->with('success', 'Paramètres d\'audit enregistrés avec succès.');
    }



    public function destroy(AuditSetting $auditSetting)
    {
        $auditSetting->delete();
        return redirect()->route('audit-settings.index')->with('success', 'Audit Setting deleted successfully.');
    }
}

