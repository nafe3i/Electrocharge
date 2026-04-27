<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class OperatorService
{

    public function firstOrCreateFromName(string $operatorName): ?User
    {
        $slug = Str::slug($operatorName, '');
        $email = strtolower($slug) . '@electrocharge.ma';

        $existing = User::where('email', $email)->first();

        if ($existing) {
            return $existing;
        }

        // Créer le nouveau compte opérateur
        $user = User::create([
            'name' => $operatorName,
            'email' => $email,
            // Password =  $slug,
            'password' => Hash::make($slug),
            'email_verified_at' => now(),
        ]);
        // dd($user);
        $user->assignRole('operator');

        \Log::info("Compte opérateur créé : {$email} / password: {$slug}");
        // DB::transaction(

        // );
        // App('db')->transaction()

        return $user;
    }


}