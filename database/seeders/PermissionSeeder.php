<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Attributaires
        Permission::create(['name' => 'ajouter un attributaire']);
        Permission::create(['name' => 'modifier un attributaire']);
        Permission::create(['name' => 'afficher un attributaire']);
        Permission::create(['name' => 'supprimer un attributaire']);
        Permission::create(['name' => 'lister les attributaires']);
        // Paramètres d'audit
        Permission::create(['name' => 'ajouter un paramètre']);
        Permission::create(['name' => 'modifier un paramètre']);
        Permission::create(['name' => 'lister les paramètres']);
        // Autorités contractantes
        Permission::create(['name' => 'ajouter une autorité']);
        Permission::create(['name' => 'modifier une autorité']);
        Permission::create(['name' => 'afficher une autorité']);
        Permission::create(['name' => 'lister les autorités']);
        // CPMP
        Permission::create(['name' => 'ajouter une CPMP']);
        Permission::create(['name' => 'modifier une CPMP']);
        Permission::create(['name' => 'afficher une CPMP']);
        Permission::create(['name' => 'lister les CPMP']);
        // Tableau de bord
        Permission::create(['name' => 'afficher le tableau de bord']);
        // Marchés
        Permission::create(['name' => 'ajouter un marché']);
        Permission::create(['name' => 'modifier un marché']);
        Permission::create(['name' => 'afficher un marché']);
        Permission::create(['name' => 'lister les marchés']);
        Permission::create(['name' => 'tirage au sort']);
        Permission::create(['name' => 'marchés à auditer']);
        // Types de marchés
        Permission::create(['name' => 'ajouter un type de marché']);
        Permission::create(['name' => 'modifier un type de marché']);
        Permission::create(['name' => 'afficher un type de marché']);
        Permission::create(['name' => 'lister les types de marchés']);
        // Modes de passation
        Permission::create(['name' => 'ajouter un mode de passation']);
        Permission::create(['name' => 'modifier un mode de passation']);
        Permission::create(['name' => 'afficher un mode de passation']);
        Permission::create(['name' => 'lister les modes de passation']);
        // Profil de l'utilisateur
        Permission::create(['name' => 'afficher un profil']);
        // Roles
        Permission::create(['name' => 'ajouter un role']);
        Permission::create(['name' => 'modifier un role']);
        Permission::create(['name' => 'afficher un role']);
        Permission::create(['name' => 'lister les roles']);
        Permission::create(['name' => 'modifier les permissions du role']);
        // Secteurs
        Permission::create(['name' => 'ajouter un secteur']);
        Permission::create(['name' => 'modifier un secteur']);
        Permission::create(['name' => 'afficher un secteur']);
        Permission::create(['name' => 'lister les secteurs']);
    }
}
