<?php

namespace App\Services;

use App\Services\Service;
use App\Repositories\StudioRepositoryInterface;
use App\Facades\GoogleMap;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use stdClass;

class StudioService extends Service
{
    protected $studioRepository;

    public function __construct(
        StudioRepositoryInterface $studioRepository
    )
    {
        $this->studioRepository = $studioRepository;
    }

    public function getAll(array $option = []): LengthAwarePaginator
    {
        return $this->studioRepository->paginate($option);
    }

    public function getStudioById(int $id): Model
    {
        return $this->studioRepository->get($id);
    }

    public function createStudio(array $studio): Model
    {
        $object = new stdClass();
        $object->name = $studio['name'];
        $object->url = $studio['url'];
        $object->tel = $studio['tel'];
        $object->zip = $studio['zip'];
        $object->prefecture = $studio['prefecture'];
        $object->city_1 = $studio['city_1'];
        $object->city_2 = $studio['city_2'];
        $object->studio_count = $studio['studio_count'] ?? 0;
        $object->open_dt = $studio['open_dt'];
        $object->end_dt = $studio['end_dt'];
        $object->cheapest_price = $studio['cheapest_price'] ?? 0;
        $object->is_web_reservation = $studio['is_web_reservation'] ?? false;

        $object->location = GoogleMap::getAddressToLocation(
            $studio['prefecture'],
            sprintf("%s%s", $studio['city_1'], $studio['city_2'])
        );

        return $this->studioRepository->create($object);
    }

    public function updateStudio(int $id, array $studio): Model
    {
        $object = new stdClass();
        $object->name = $studio['name'];
        $object->url = $studio['url'];
        $object->tel = $studio['tel'];
        $object->zip = $studio['zip'];
        $object->prefecture = $studio['prefecture'];
        $object->city_1 = $studio['city_1'];
        $object->city_2 = $studio['city_2'];
        $object->studio_count = $studio['studio_count'] ?? 0;
        $object->open_dt = $studio['open_dt'];
        $object->end_dt = $studio['end_dt'];
        $object->cheapest_price = $studio['cheapest_price'] ?? 0;
        $object->is_web_reservation = $studio['is_web_reservation'] ?? false;

        $object->location = GoogleMap::getAddressToLocation(
            $studio['prefecture'],
            sprintf("%s%s", $studio['city_1'], $studio['city_2'])
        );

        return $this->studioRepository->update($id, $object);
    }

    public function deleteStudio(int $id): bool
    {
        return $this->studioRepository->delete($id);
    }
}