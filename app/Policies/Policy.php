<?php

namespace App\Policies;

use App\Repositories\Eloquent\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class Policy
{
    use HandlesAuthorization;

    public function __construct()
    {
    }

    public function before(User $user, $ability)
    {
        if ($user->isSystem() || $user->isAdmin())
        {
            return true;
        }
    }
}
