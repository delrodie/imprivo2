<?php

namespace App\Enum;

enum ClientType: string
{
    case PARTICULIER = 'PARTICULIER';
    case ENTREPRISE = 'ENTREPRISE';
    case INSTITUTION = 'INSTITUTION';
    case AUTRE = 'AUTRE';
}
