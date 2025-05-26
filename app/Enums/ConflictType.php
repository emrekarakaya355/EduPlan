<?php

namespace App\Enums;

enum ConflictType: string
{
    case BLOCKING = 'blocking';
    case WARNING = 'warning';
}
