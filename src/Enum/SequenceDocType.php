<?php

namespace App\Enum;

enum SequenceDocType: string
{
    case DEV = 'DEV';
    case FAC = 'FAC';
    case REG = 'REG';
    case TR = 'TR';
}
