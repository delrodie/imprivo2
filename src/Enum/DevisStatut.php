<?php

namespace App\Enum;

enum DevisStatut: string
{
    case BROUILLON = 'BROUILLON';
    case ENVOYE = 'ENVOYE';
    case ACCEPTE = 'ACCEPTE';
    case REFUSE = 'REFUSE';
    case EXPIRE = 'EXPIRE';
    case TRANSFORME = 'TRANSFORME';
}
