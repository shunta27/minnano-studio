<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use stdClass;

interface UserRepositoryInterface
{
    /**
     * Undocumented function
     *
     * @param integer $id
     * @return Illuminate\Database\Eloquent\Model
     */
    public function get(int $id): Model;

    /**
     * Undocumented function
     *
     * @param string $email
     * @return Illuminate\Database\Eloquent\Model
     */
    public function getUserByEmail(string $email): Model;

    /**
     * Undocumented function
     *
     * @param stdClass $credentials
     * @return Illuminate\Database\Eloquent\Model
     */
    public function create(stdClass $credentials): Model;
}