<?php

namespace App\Exceptions\Traits;

use Illuminate\Http\Request;

trait RestTrait
{
    /**
     * Determines if request is an api call.
     *
     * @param \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function isApiCall(Request $request): bool
    {
        return strpos($request->getUri(), '/api/') !== false;
    }
}