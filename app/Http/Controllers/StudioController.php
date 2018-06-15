<?php

namespace App\Http\Controllers;

use App\Repositories\Eloquent\Models\Studio;
use App\Services\StudioService;
use App\Http\Resources\Resource;
use App\Http\Requests\StudioCreateRequest;
use Illuminate\Support\Facades\Request;
use stdClass;

class StudioController extends Controller
{
    /**
     * @var \App\Services\StudioService
     */
    protected $studioService;

    public function __construct(
        StudioService $studioService
    )
    {
        $this->studioService = $studioService;
    }

    public function index(): Resource
    {
        return new Resource(
            $this->studioService->getAll([
                'q' => Request::query(),
            ])
        );
    }

    public function show(Studio $studio): Resource
    {
        return new Resource(
            $this->studioService->getStudioById($studio->id)
        );
    }

    public function store(StudioCreateRequest $request): Resource
    {
        $this->authorize('store', Studio::class);

        return new Resource(
            $this->studioService->createStudio(
                $request->toArray()
            )
        );
    }

    public function update(StudioCreateRequest $request, Studio $studio): Resource
    {
        $this->authorize('update', $studio);
        
        return new Resource(
            $this->studioService->updateStudio(
                $studio->id,
                $request->toArray()
            )
        );
    }

    public function destroy(Studio $studio): Resource
    {
        $this->authorize('destroy', $studio);

        $this->studioService->deleteStudio($studio->id);

        return new Resource([]);
    }
}
