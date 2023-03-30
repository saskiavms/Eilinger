<?php

namespace App\Enums;

enum Education: string
{
    case Matura ='Matura';
    case FMS = 'FMS';
    case Berufslehre = 'Berufslehre';
    case BM2 = 'BM2';
    case Fachschule = 'Fachschule';
    case Fachhochschule = 'Fachhochschule';
    case Universität = 'Universität';

    public static function values(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }
}