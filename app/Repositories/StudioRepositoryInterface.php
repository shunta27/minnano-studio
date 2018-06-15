<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use stdClass;

interface StudioRepositoryInterface
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
     * @param array $option
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginate(array $option = []): LengthAwarePaginator;

    /**
     * Undocumented function
     *
     * @param int $id
     * @return Illuminate\Database\Eloquent\Model
     */
    public function get(int $id): Model;

    /**
     * Undocumented function
     *
     * @param stdClass $object
     * @return Illuminate\Database\Eloquent\Model
     */
    public function create(stdClass $object): Model;

    /**
     * Undocumented function
     *
     * @param int $id
     * @param stdClass $object
     * @return Illuminate\Database\Eloquent\Model
     */
    public function update(int $id, stdClass $object): Model;

    /**
     * Undocumented function
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
