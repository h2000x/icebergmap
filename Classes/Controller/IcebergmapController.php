<?php

namespace Justabunchof\Icebergmap\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Resource\StorageRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class IcebergmapController extends ActionController
{
    public function __construct(
        private ExtensionConfiguration $extensionConfiguration,
        private StorageRepository $storageRepository,
    )
    {
        $this->extensionKey = GeneralUtility::camelCaseToLowerCaseUnderscored(explode('\\', $this::class )[1] );
        $this->extConf = $this->extensionConfiguration->get($this->extensionKey);

    }
    public function showAction(): ResponseInterface
    {
        $storage = $this->storageRepository->getDefaultStorage();
        $kmlFile  = $storage->getRootLevelFolder()->getPublicUrl() . $this->extConf['kmlSavePath'] . 'iceberg.kml';

        $this->view->assign('kmlFile',$kmlFile);
        $this->view->assign('startingPoint',$this->extConf['startingPoint']);
        $this->view->assign('startingZoom',$this->extConf['startingZoom']);

        return $this->htmlResponse();

    }
}