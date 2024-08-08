<?php

namespace App\Domain\Entity\Enum;

enum UserTypeEnum: string
{
    case admin = 'admin';
    case regular = 'regular';
}
