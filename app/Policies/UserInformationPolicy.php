<?php

namespace App\Policies;

use App\Policies\Policy;
use App\Repositories\Eloquent\Models\User;
use App\Repositories\Eloquent\Models\UserInformation;

class UserInformationPolicy extends Policy
{
    public function index(User $use)
    {
    }

    public function show(User $user, UserInformation $userInformation)
    {
        return $user->id == $userInformation->user_id;
    }

    public function store(User $use)
    {
    }

    public function update(User $user, UserInformation $userInformation)
    {
        return $user->id == $userInformation->user_id;
    }

    public function destroy(User $user, UserInformation $userInformation)
    {
        return $user->id == $userInformation->user_id;
    }
}
