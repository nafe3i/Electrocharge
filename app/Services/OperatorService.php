<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OperatorService
{
    /**
     * Créer ou récupérer le compte opérateur
     * à partir du nom de l'opérateur OCM
     * ----------------------------------------
     * Exemple :
     * "Fastvolt"  → fastvolt@electrocharge.ma
     * "ONEE"      → onee@electrocharge.ma
     * "Afriquia"  → afriquia@electrocharge.ma
     * ----------------------------------------
     */
    public function firstOrCreateFromName(string $operatorName): ?User
    {
        // Nettoyer le nom pour créer un email valide
        // "Fast Volt Morocco" → "fastvoltnoracco"
        $slug = Str::slug($operatorName, '');
        $email = strtolower($slug) . '@electrocharge.ma';

        // Vérifier si le compte existe déjà
        $existing = User::where('email', $email)->first();

        if ($existing) {
            return $existing;
        }

        // Créer le nouveau compte opérateur
        $user = User::create([
            'name' => $operatorName,
            'email' => $email,
            // Password = slug du nom (ex: "fastvolt")
            'password' => Hash::make($slug),
            'email_verified_at' => now(), // vérifié automatiquement
        ]);

        // Assigner le rôle operator
        $user->assignRole('operator');

        \Log::info("Compte opérateur créé : {$email} / password: {$slug}");

        return $user;
    }
}