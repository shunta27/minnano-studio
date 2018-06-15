<?php

namespace App\Repositories\Eloquent;

use App\Repositories\UserInformationRepositoryInterface;
use App\Repositories\Eloquent\Models\UserInformation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use stdClass;

class UserInformationRepository implements UserInformationRepositoryInterface
{
    protected $user_information;

    public function __construct(UserInformation $user_information)
    {
        $this->user_information = $user_information;
    }

    public function all(): Collection
    {
        return $this->user_information
            ->with('user')
            ->get();
    }

    public function paginate(): LengthAwarePaginator
    {
        return $this->user_information
            ->with('user')
            ->paginate(20);
    }

    public function get(int $id): Model
    {
        return $this->user_information
            ->with('user')
            ->findOrFail($id);
    }

    public function create(stdClass $object): Model
    {
        $user_information = $this->user_information->newInstance([
            'user_id' => $object->user_id,
            'name' => $object->name,
            'enabled' => $object->enabled,
        ]);

        $user_information->save();

        return $this->get($user_information->id);
    }

    public function update(int $id, string $name, int $enabled): Model
    {
        $user_information = $this->get($id);

        $user_information->name = $name;
        $user_information->enabled = $enabled;

        $user_information->save();

        return $this->get($user_information->id);
    }

    public function delete(int $id): bool
    {
        return $this->get($id)
            ->delete();
    }
}
