<?php

namespace App\Enum;

enum FactureStatut: string
{
    case BROUILLON = 'BROUILLON';
    case VALIDEE = 'VALIDEE';
    case PARTIELLEMENT_PAYEE = 'PARTIELLEMENT_PAYEE';
    case PAYEE = 'PAYEE';
    case IMPAYEE = 'IMPAYEE';
    case ANNULEE = 'ANNULEE';
}
