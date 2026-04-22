<?php

namespace App\Policies;

use App\Models\Station;
use App\Models\User;

class StationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view-stations');
    }

    public function view(User $user, Station $station): bool
    {
        return $station->is_active || $user->can('manage-stations');
    }

    public function create(User $user): bool
    {
        return $user->can('manage-stations');
    }

    public function update(User $user, Station $station): bool
    {
        return $user->can('manage-stations');
    }

    public function delete(User $user, Station $station): bool
    {
        return $user->can('manage-stations');
    }
    /**
     * L'opérateur peut voir les stats de sa propre station
     */
    public function viewOwn(User $user, Station $station): bool
    {
        return $user->hasRole('operator')
            && $station->operator_id === $user->id;
    }
}
