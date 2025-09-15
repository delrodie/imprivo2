<?php

namespace App\Services;

use App\Repository\ClientRepository;
use App\Repository\EmployeRepository;

class UtilityService
{
    public function __construct(
        private EmployeRepository $employeRepository,
        private ClientRepository $clientRepository
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

    public function codeClient(): string
    {
        do{
            $aleatoire = str_pad((int)random_int(0, 999), 3, '0', STR_PAD_LEFT);
            $code = date('ym') . $aleatoire;
        } while($this->clientRepository->findOneBy(['code' => $code]));

        return $code;
    }
}
