<?php

namespace App\Services;

use App\Services\Service;
use App\Repositories\UserRepositoryInterface;
use stdClass;

class UserService extends Service
{
    protected $userRepository;

    public function __construct(
        UserRepositoryInterface $userRepository
    )
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Create a new user.
     *
     * @param mixed $credentials (name, email, password)
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createUser(array $credentials = [])
    {
        $object = new stdClass();
        $object->email = $credentials['email'];
        $object->password = $credentials['password'];
        $user = $this->userRepository->create($object);

        return $user;
    }
}