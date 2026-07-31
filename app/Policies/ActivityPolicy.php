<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Activity;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActivityPolicy
{
    use HandlesAuthorization;

    /**
     * Determina si el usuario puede ver una lista de actividades.
     */
    public function viewAny(User $user)
    {
        // Lógica para permitir ver todas las actividades
        return $user->hasRole('admin') || $user->hasRole('SuperAdministrador');
    }

    /**
     * Determina si el usuario puede ver una actividad específica.
     */
    public function view(User $user, Activity $activity)
    {
        // Lógica para permitir ver una actividad en particular
        return $user->id === $activity->user_id || $user->hasRole('admin');
    }

    /**
     * Determina si el usuario puede crear una nueva actividad.
     */
    public function create(User $user)
    {
        return $user->hasRole('SuperAdministrador');
    }

    /**
     * Determina si el usuario puede actualizar una actividad.
     */
    public function update(User $user, Activity $activity)
    {
        return $user->id === $activity->user_id || $user->hasRole('SuperAdministrador');
    }

    /**
     * Determina si el usuario puede eliminar una actividad.
     */
    public function delete(User $user, Activity $activity)
    {
        return $user->id === $activity->user_id || $user->hasRole('SuperAdministrador');
    }

    /**
     * Determina si el usuario puede restaurar una actividad eliminada.
     */
    public function restore(User $user, Activity $activity)
    {
        return $user->hasRole('SuperAdministrador');
    }

    /**
     * Determina si el usuario puede eliminar permanentemente una actividad.
     */
    public function forceDelete(User $user, Activity $activity)
    {
        return $user->hasRole('SuperAdministrador');
    }
}
