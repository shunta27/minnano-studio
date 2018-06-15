<?php

namespace App\Http\Controllers;

use App\Repositories\Eloquent\Models\UserInformation;
use App\Services\UserInformationService;
use App\Http\Resources\Resource;
use App\Http\Requests\UserInformationUpdateRequest;

class UserInformationController extends Controller
{
    /**
     * @var \App\Services\UserInformationService
     */
    protected $userInformationService;

    public function __construct(
        UserInformationService $userInformationService
    )
    {
        $this->userInformationService = $userInformationService;
    }

    public function index(): Resource
    {
        $this->authorize('index', UserInformation::class);

        return new Resource($this->userInformationService->getAll());
    }

    public function show(UserInformation $userInformation): Resource
    {
        $this->authorize('show', $userInformation);

        return new Resource(
            $this->userInformationService->getUserInformaionById($userInformation->id)
        );
    }

    public function update(UserInformationUpdateRequest $request, UserInformation $userInformation): Resource
    {
        $this->authorize('update', $userInformation);

        return new Resource(
            $this->userInformationService->updateUserInformation(
                $userInformation->id,
                $request->name,
                $request->enabled
            )
        );
    }

    public function destroy(UserInformation $userInformation): Resource
    {
        $this->authorize('destroy', $userInformation);

        $this->userInformationService->deleteUserInformation($userInformation->id);
        
        return new Resource([]);
    }
}
