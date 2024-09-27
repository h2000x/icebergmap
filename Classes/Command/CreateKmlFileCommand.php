<?php

declare(strict_types=1);

namespace Justabunchof\Icebergmap\Command;

use Justabunchof\Icebergmap\Service\KmlFileService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateKmlFileCommand extends Command
{

    public function __construct(
        private KmlFileService $kmlFileService,
        string $name = null
    )
    {
        parent::__construct($name);
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->kmlFileService->createKmlFile();
        return Command::SUCCESS;
    }
}