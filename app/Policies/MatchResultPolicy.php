<?php

namespace App\Policies;

use App\Models\MatchResult;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MatchResultPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, MatchResult $match): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, MatchResult $match): bool
    {
        return true;
    }

    public function delete(User $user, MatchResult $match): bool
    {
        return true;
    }

    public function restore(User $user, MatchResult $match): bool
    {
        return true;
    }

    public function forceDelete(User $user, MatchResult $match): bool
    {
        return true;
    }
}
