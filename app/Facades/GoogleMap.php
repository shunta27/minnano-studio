<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class GoogleMap extends Facade {
    protected static function getFacadeAccessor() {
        return 'google_map';
    }
}