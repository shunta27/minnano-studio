<?php

namespace App\Services;

use App\Services\Service;
use App\Repositories\UserInformationRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use stdClass;

class UserInformationService extends Service
{
    protected $userInformationRepository;

    public function __construct(
        UserInformationRepositoryInterface $userInformationRepository
    )
    {
        $this->userInformationRepository = $userInformationRepository;
    }

    public function getAll(): LengthAwarePaginator
    {
        return $this->userInformationRepository->paginate();
    }

    public function getUserInformaionById(int $id): Model
    {
        return $this->userInformationRepository->get($id);
    }

    public function createUserInformation(stdClass $user_information): Model
    {
        return $this->userInformationRepository->create($user_information);
    }

    public function updateUserInformation(int $id, string $name, int $enabled): Model
    {
        return $this->userInformationRepository->update($id, $name, $enabled);
    }

    public function deleteUserInformation(int $id): bool
    {
        return $this->userInformationRepository->delete($id);
    }
}
