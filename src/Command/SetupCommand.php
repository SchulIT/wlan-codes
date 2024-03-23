<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\PdoSessionHandler;
use Doctrine\ORM\EntityManagerInterface;

#[AsCommand('app:setup', 'Installiert die Anwendungen')]
class SetupCommand extends Command {

    public function __construct(private readonly EntityManagerInterface $em, private readonly PdoSessionHandler $pdoSessionHandler, string $name = null) {
        parent::__construct($name);
    }

    public function execute(InputInterface $input, OutputInterface $output): int {
        $style = new SymfonyStyle($input, $output);

        $this->setupSessions($style);

        return 0;
    }

    private function setupSessions(SymfonyStyle $style) {
        $sql = "SHOW TABLES LIKE 'sessions';";
        $row = $this->em->getConnection()->executeQuery($sql);

        if($row->fetchAssociative() === false) {
            $this->pdoSessionHandler->createTable();
        }

        $style->success('Sessions-Tabelle erstellt');
    }

}