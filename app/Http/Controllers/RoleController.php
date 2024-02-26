<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all(); // Fetch all roles
        return view('roles.index', compact('roles'));
    }

    public function show(Role $role)
    {
        $permissions = Permission::all(); // Fetch all permissions
        return view('roles.show', compact('role', 'permissions'));
    }

    public function create()
    {
        return view('roles.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate(
            ['name' => 'required|string|max:255|unique:roles']
        );
        Role::create(['name' => $validatedData['name']]);
        return redirect()->route('roles.index')->with(
            'success',
            'Groupe ajouté avec succès.'
        );
    }

    public function updatePermissions(Request $request, Role $role)
    {
        $permissions = json_decode($request->permissions);
        $role->syncPermissions($permissions);
        return back()->with('success', 'Permissions mises à jour avec succès.');
    }
}
