<?php

declare(strict_types=1);

namespace Justabunchof\Icebergmap\Command;

use Justabunchof\Icebergmap\Utility\KmlFileUtility;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\StorageRepository;


class CreateKmlFileCommand extends Command
{

    protected array $extConf;
    public function __construct(
        private KmlFileUtility $kmlFileUtility,
        private ExtensionConfiguration $extensionConfiguration,
        private StorageRepository $storageRepository,
        string $name = null
    )
    {
        $this->extConf = $this->extensionConfiguration->get('icebergmap');
        $this->extensionKey = GeneralUtility::camelCaseToLowerCaseUnderscored(explode('\\', $this::class )[1] );
        $varPath = Environment::getVarPath();
        $this->data_path = $varPath . '/' . strtolower($this->extensionKey) . '/';

        parent::__construct($name);
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $xmlOutput = $this->kmlFileUtility->renderTemplate();
        $ok = file_put_contents($this->data_path . 'iceberg.tmp.xml', $xmlOutput);
        $this->storeFile($this->data_path . 'iceberg.tmp.xml');
//        $this->kmlFileService->createKmlFile();
        return Command::SUCCESS;
    }

    private function storeFile(string $file_path)
    {
        $storage = $this->storageRepository->getDefaultStorage();

        /** @var File $newFile */
        $newFile = $storage->addFile(
            $file_path,
            $storage->getRootLevelFolder(),
            'iceberg.kml',
        );
        
        echo '$newFile' . "<br>\n";
        print_r(get_class_methods($newFile));
        echo $newFile->getIdentifier()  . "<br>\n";
         
    }
}