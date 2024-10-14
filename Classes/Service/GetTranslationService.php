<?php

namespace Justabunchof\Icebergmap\Service;

use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class GetTranslationService
{
    //@todo better name for $tag
    public function translate(string $tag): string
    {
        $languageService = $this->getLanguageService();
        return $languageService->sL('LLL:EXT:icebergmap/Resources/Private/Language/locallang_db.xlf:' . $tag);
    }
    private function getLanguageService(): LanguageService
    {
        return GeneralUtility::makeInstance(LanguageServiceFactory::class)
            ->createFromUserPreferences($GLOBALS['BE_USER']);
    }
}