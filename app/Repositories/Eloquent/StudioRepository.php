<?php

namespace App\Repositories\Eloquent;

use App\Repositories\StudioRepositoryInterface;
use App\Repositories\Eloquent\Models\Studio;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use stdClass;

class StudioRepository implements StudioRepositoryInterface
{
    /**
     * @var \App\Repositories\Eloquent\Models\Studio
     */
    protected $studio;
    
    public function __construct(Studio $studio)
    {
        $this->studio = $studio;
    }

    public function all(): Collection
    {
        return $this->studio->get();
    }

    public function paginate(array $option = []): LengthAwarePaginator
    {
        return $this->studio
            ->asSearch($option)
            ->paginate(10);
    }

    public function get(int $id): Model
    {
        return $this->studio
            ->withComments()
            ->findOrFail($id);
    }

    public function create(stdClass $object): Model
    {
        $studio = $this->studio->newInstance([
            'name' => $object->name,
            'url' => $object->url,
            'tel' => $object->tel,
            'zip' => $object->zip,
            'prefecture' => $object->prefecture,
            'name' => $object->name,
            'city_1' => $object->city_1,
            'city_2' => $object->city_2,
            'location' => $object->location,
            'studio_count' => $object->studio_count,
            'open_dt' => $object->open_dt,
            'end_dt' => $object->end_dt,
            'cheapest_price' => $object->cheapest_price,
            'is_web_reservation' => $object->is_web_reservation,
        ]);

        $studio->save();

        return $this->get($studio->id);
    }

    public function update(int $id, stdClass $object): Model
    {
        $studio = $this->get($id);

        $studio->name = $object->name;
        $studio->url = $object->url;
        $studio->tel = $object->tel;
        $studio->zip = $object->zip;
        $studio->prefecture = $object->prefecture;
        $studio->city_1 = $object->city_1;
        $studio->city_2 = $object->city_2;
        $studio->location = $object->location;
        $studio->studio_count = $object->studio_count;
        $studio->open_dt = $object->open_dt;
        $studio->end_dt = $object->end_dt;
        $studio->cheapest_price = $object->cheapest_price;
        $studio->is_web_reservation = $object->is_web_reservation;

        $studio->save();

        return $this->get($studio->id);
    }

    public function delete(int $id): bool
    {
        $studio = $this->get($id);

        return $studio->delete();
    }
}
