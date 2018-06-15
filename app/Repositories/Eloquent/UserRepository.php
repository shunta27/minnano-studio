<?php

namespace App\Repositories\Eloquent;

use App\Repositories\UserRepositoryInterface;
use App\Repositories\Eloquent\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use stdClass;


class UserRepository implements UserRepositoryInterface
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function get(int $id): Model
    {
        return $this->user
            ->with('user_information')
            ->findOrFail($id);
    }

    public function getUserByEmail(string $email): Model
    {
        return $this->user
            ->with('user_information')
            ->where('email', $email)
            ->firstOrFail();
    }

    public function create(stdClass $credentials): Model
    {
        $user = $this->user->newInstance([
            'email' => $credentials->email,
            'password' => Hash::make($credentials->password),
            'role' => $credentials->role,
        ]);

        $user->save();

        return $this->get($user->id);
    }
}