<?php

namespace App\Enums;

enum RoleType: string
{
    case OWNER = 'owner';
    case MEMBER = 'member';
    case CUSTOM = 'custom';

	public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}