<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ApiAuth extends Facade {
    protected static function getFacadeAccessor() {
        return 'api_auth';
    }
}