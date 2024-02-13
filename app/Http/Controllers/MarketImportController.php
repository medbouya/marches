<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MarketsImport;
use Illuminate\Support\Facades\Storage;

class MarketImportController extends Controller
{
    public function importIndex()
    {
        return view('import');
    }

    public function import(Request $request)
    {
        // Validate the request to ensure a file is uploaded
        $request->validate([
            'file' => 'required|file',
        ]);

        // Store the uploaded file temporarily
        $file = $request->file('file');
        $filePath = $file->store('temp'); // Stores in 'storage/app/temp'

        // Import the file
        Excel::import(new MarketsImport, storage_path('app/' . $filePath));

        // Optionally, delete the file after import
        Storage::delete($filePath);

        return back()->with('message', 'Données importées avec succès.');
    }
}
