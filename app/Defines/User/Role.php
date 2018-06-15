<?php

namespace App\Defines\User;

use App\Utils\Enum;

final class Role extends Enum
{
    const DEFAULT = 0;
    const SYSTEM = 1;
    const ADMIN = 5;
    const USER = 10;
}