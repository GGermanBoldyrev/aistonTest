<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TicketPolicy
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
    public function view(User $user, Ticket $ticket): bool
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
    public function update(User $user, Ticket $ticket): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Ticket $ticket): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Ticket $ticket): bool
    {
        return true;
    }

    /**
     * Связи hasOne
     */
    public function viewPharmacy(?User $user, Ticket $ticket): bool
    {
        return $this->view($user, $ticket);
    }

    public function viewPriority(?User $user, Ticket $ticket): bool
    {
        return $this->view($user, $ticket);
    }

    public function viewStatus(?User $user, Ticket $ticket): bool
    {
        return $this->view($user, $ticket);
    }

    public function viewCategory(?User $user, Ticket $ticket): bool
    {
        return $this->view($user, $ticket);
    }

    public function viewTechnician(?User $user, Ticket $ticket): bool
    {
        return $this->view($user, $ticket);
    }

    /**
     * Связь hasMany
     */
    public function viewAttachments(?User $user, Ticket $ticket): bool
    {
        return $this->view($user, $ticket);
    }
}
