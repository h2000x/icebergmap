<?php

declare(strict_types=1);

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use Justabunchof\Icebergmap\Controller\IcebergmapController;

defined('TYPO3') or die();

ExtensionUtility::configurePlugin(
// extension name, matching the PHP namespaces (but without the vendor)
    'Icebergmap',
    // arbitrary, but unique plugin name (not visible in the backend)
    'MapViewer',
    // all actions
    [
        IcebergmapController::class => 'show',
    ],
    // non-cacheable actions
    [
        IcebergmapController::class => 'show'
    ],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);