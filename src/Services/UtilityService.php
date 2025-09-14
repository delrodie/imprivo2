<?php

namespace App\Services;

use App\Repository\EmployeRepository;

class UtilityService
{
    public function __construct(
        private EmployeRepository $employeRepository
    )
    {
    }

    /**
     * Generation du code employé
     * @return string
     */
    public function codeEmploye(): string
    {
        do{
            $aleatoire = str_pad((int)random_int(0, 99), 2, '0', STR_PAD_LEFT);
            $code = date('ym') . $aleatoire;
        } while($this->employeRepository->findOneBy(['code' => $code]));

        return $code;
    }
}
