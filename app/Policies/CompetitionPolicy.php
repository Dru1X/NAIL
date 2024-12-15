<?php

namespace App\Policies;

use App\Models\Competition;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompetitionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Competition $competition): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Competition $competition): bool
    {
        return true;
    }

    public function delete(User $user, Competition $competition): bool
    {
        return true;
    }

    public function restore(User $user, Competition $competition): bool
    {
        return true;
    }

    public function forceDelete(User $user, Competition $competition): bool
    {
        return true;
    }
}
