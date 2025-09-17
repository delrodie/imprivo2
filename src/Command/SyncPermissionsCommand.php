<?php

namespace App\Command;

use App\Entity\Permission;
use App\Repository\PermissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\KernelInterface;

#[AsCommand(
    name: 'app:sync-permissions',
    description: 'Synchronisation des permissions définies dans config/permissions.php avec la base de données',
)]
class SyncPermissionsCommand extends Command
{
    private string $projectDir;
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly PermissionRepository $permissionRepository,
        KernelInterface $kernel
    )
    {
        parent::__construct();
        $this->projectDir = $kernel->getProjectDir();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $file = $this->projectDir . '/config/permissions.php';
        if (!file_exists($file)) {
            $io->error("Le fichier $file est introuvable.");
            return Command::FAILURE;
        }

        $permissionsConfig = require $file;

        foreach ($permissionsConfig as $perm) {
            $existing = $this->permissionRepository->findOneBy(['code' => $perm['code']]);

            if($existing){
                if ($existing->getLabel() !== $perm['label']){
                    $existing->setLabel($perm['label']);
                    $io->text("✅ Mise à jour du label pour {$perm['code']}");
                }
            } else{
                $permission = new Permission();
                $permission->setCode($perm['code']);
                $permission->setLabel($perm['label']);
                $this->entityManager->persist($permission);

                $io->text("➕ Ajout de {$perm['code']}");
            }
        }

        $this->entityManager->flush();

        $io->success('Synchronisation des permissions terminée!');

        return Command::SUCCESS;
    }
}
