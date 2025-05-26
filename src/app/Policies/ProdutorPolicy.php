<?php

namespace App\Policies;

use App\Models\Produtor;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProdutorPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Produtor $produtor): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        $this->authorize('create', Produtor::class);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Produtor $produtor): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Produtor $produtor): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Produtor $produtor): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Produtor $produtor): bool
    {
        //
    }
}
