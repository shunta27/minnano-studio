<?php

namespace App\Exceptions\Traits;

use App\Utils\Enum;

final class StatusCode extends Enum
{
    const BAD_REQUEST = 400;
    const UNAUTHENTICATED = 401;
    const FORBIDDEN = 403;
    const RECORD_NOT_FOUND = 404;
    const METHOD_NOT_ALLOWED = 405;
    const VALIDATION_ERROR = 422;
    const INTERNAL_SERVER_ERROR = 500;
}