<?php

namespace App\Http\Resources;

use Illuminate\Support\Collection;
use Illuminate\Http\Resources\Json\JsonResource;

class Resource extends JsonResource
{
    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     * @return void
     */
    public function __construct($resource)
    {
        if (!($resource instanceof Collection))
        {
            $resource = new Collection($resource);
        }

        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
