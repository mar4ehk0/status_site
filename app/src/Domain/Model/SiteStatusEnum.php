<?php

namespace App\Domain\Model;

enum SiteStatusEnum: string
{
    case Up = 'up';
    case Down = 'down';
}
