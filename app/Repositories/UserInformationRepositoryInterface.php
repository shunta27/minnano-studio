<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use stdClass;

interface UserInformationRepositoryInterface
{
    /**
     * Undocumented function
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all(): Collection;

    /**
     * Undocumented function
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginate(): LengthAwarePaginator;

    /**
     * Undocumented function
     *
     * @param int $id
     * @return Illuminate\Database\Eloquent\Model
     */
    public function get(int $id): Model;

    /**
     * Create a new user information.
     *
     * @param stdClass $object
     * @return Illuminate\Database\Eloquent\Model
     */
    public function create(stdClass $object): Model;

    /**
     * Undocumented function
     *
     * @param int $id
     * @param string $name
     * @param int $enabled
     * @return Illuminate\Database\Eloquent\Model
     */
    public function update(int $id, string $name, int $enabled): Model;

    /**
     * Undocumented function
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
