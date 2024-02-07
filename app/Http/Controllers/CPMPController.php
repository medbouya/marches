<?php

namespace App\Http\Controllers;

use App\Models\CPMP;
use Illuminate\Http\Request;

class CPMPController extends Controller
{
    public function index()
    {
        $cpmps = CPMP::all();
        return view('cpmps.index', compact('cpmps'));
    }

    public function create()
    {
        return view('cpmps.create');
    }

    public function store(Request $request)
    {
        CPMP::create($request->all());
        return redirect()->route('cpmps.index')->with('success', 'CPMP created successfully.');
    }

    public function show(CPMP $cpmp)
    {
        return view('cpmps.show', compact('cpmp'));
    }

    public function edit(CPMP $cpmp)
    {
        return view('cpmps.edit', compact('cpmp'));
    }

    public function update(Request $request, CPMP $cpmp)
    {
        $cpmp->update($request->all());
        return redirect()->route('cpmps.index')->with('success', 'CPMP updated successfully.');
    }

    public function destroy(CPMP $cpmp)
    {
        $cpmp->delete();
        return redirect()->route('cpmps.index')->with('success', 'CPMP deleted successfully.');
    }
}
