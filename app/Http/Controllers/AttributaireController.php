<?php

namespace App\Http\Controllers;

use App\Models\Attributaire;
use Illuminate\Http\Request;

class AttributaireController extends Controller
{
    public function __construct()
    {
        // Permissions
        $this->middleware('permission:afficher un attributaire', ['only' => ['show']]);
        $this->middleware('permission:lister les attributaires', ['only' => ['index']]);
        $this->middleware('permission:ajouter un attributaire', ['only' => ['create', 'store']]);
        $this->middleware('permission:modifier un attributaire', ['only' => ['edit', 'update']]);
        $this->middleware('permission:supprimer un attributaire', ['only' => ['destroy']]);
    }

    public function index()
    {
        $attributaires = Attributaire::paginate(10);
        return view('attributaires.index', compact('attributaires'));
    }

    public function create()
    {
        return view('attributaires.create');
    }

    public function store(Request $request)
    {
        Attributaire::create($request->all());
        return redirect()->route('attributaires.index')->with('success', 'Attributaire created successfully.');
    }

    public function show(Attributaire $attributaire)
    {
        return view('attributaires.show', compact('attributaire'));
    }

    public function edit(Attributaire $attributaire)
    {
        return view('attributaires.edit', compact('attributaire'));
    }

    public function update(Request $request, Attributaire $attributaire)
    {
        $attributaire->update($request->all());
        return redirect()->route('attributaires.index')->with('success', 'Attributaire updated successfully.');
    }

    public function destroy(Attributaire $attributaire)
    {
        $attributaire->delete();
        return redirect()->route('attributaires.index')->with('success', 'Attributaire deleted successfully.');
    }
}

