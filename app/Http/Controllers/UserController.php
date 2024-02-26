<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        $roles = Role::all();
        return view('users.index', compact('users', 'roles'));
    }

    public function assignRole(Request $request, User $user)
    {
        $roleName = $request->role;
        $user->assignRole($roleName);
        return back()->with('success', 'Groupe assigné avec succès.');
    }

    public function showRegistrationForm()
    {
        return view('users.register');
    }

    public function customRegister(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        return redirect()->route('users.index')->with('success', 'Utilisateur ajouté avec succès.');
    }

    // Validate the user registration request
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            // Add other fields here
        ]);
    }

    // Create a new user instance after a valid registration
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            // Handle other fields here
        ]);
    }
}
