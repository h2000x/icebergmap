<?php

declare(strict_types=1);

namespace Justabunchof\Icebergmap\Utility;

use Justabunchof\Icebergmap\Domain\Repository\IcebergDataRepository;
use Justabunchof\Icebergmap\Domain\Repository\IcebergRepository;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class KmlFileUtility
{

    public function __construct(
        private IcebergRepository $icebergRepository,
        private IcebergDataRepository $icebergDataRepository
    )
    {

    }

    public function renderTemplate()
    {
        $icebergs = $this->getAllIcebergData();
        // Instanz der StandaloneView erstellen
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setPartialRootPaths(['EXT:icebergmap/Resources/Private/Partials']);

        // Fluid-Template-Datei festlegen
        $view->setTemplatePathAndFilename('EXT:icebergmap/Resources/Private/Templates/KmlTemplate.xml');
        
        // Variablen an das Template übergeben
        $view->assignMultiple([
            'icebergs' => $icebergs,
            'anotherVar' => 'Eine zweite Variable'
        ]);

        // Template rendern und als HTML zurückgeben
        return $view->render();
    }
    private function getAllIcebergData()
    {
        $icebergs = $this->icebergRepository->findAll();
        $result = $icebergs->toArray();
        foreach ($result as $single_iceberg) {
            $single_iceberg->setIcebergData($this->getDataForIceberg($single_iceberg));
        }
        return $result;
    }
    private function getDataForIceberg(mixed $single_iceberg)
    {
        return $this->icebergDataRepository->findDataSortByDate($single_iceberg);
    }
}