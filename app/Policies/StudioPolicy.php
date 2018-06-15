<?php

namespace App\Policies;

use App\Policies\Policy;
use App\Repositories\Eloquent\Models\User;
use App\Repositories\Eloquent\Models\Studio;

class StudioPolicy extends Policy
{
    public function index(User $user)
    {
    }

    public function show(User $user, Studio $studio)
    {
    }

    public function store(User $user)
    {
    }

    public function update(User $user, Studio $studio)
    {
    }

    public function destroy(User $user, Studio $studio)
    {
    }
}
