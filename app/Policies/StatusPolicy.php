<?php

namespace App\Policies;

use App\Models\Status;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StatusPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Status $status): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Status $status): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Status $status): Response
    {
        return $status->tickets()->doesntExist()
            ? Response::allow()
            : Response::deny('Нельзя удалить статус, к которому привязаны тикеты.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Status $status): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Status $status): bool
    {
        return true;
    }

    /**
     * Связь hasMany
     */
    public function viewTickets(?User $user, Status $status): bool
    {
        return $this->view($user, $status);
    }
}
