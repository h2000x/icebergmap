<?php

namespace Justabunchof\Icebergmap\Command;

use Justabunchof\Icebergmap\Request\IcebergCsvRequester;
use Justabunchof\Icebergmap\Service\GetTranslationService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Registry;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class GetCsvFileCommand extends Command
{
    public function __construct(
        private IcebergCsvRequester $icebergCsvRequester,
        private readonly Registry $registry,
        private GetTranslationService $getTranslationService,
        string $name = null
    )
    {
        parent::__construct($name);
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->icebergCsvRequester->request();
        $csvFilename = $this->icebergCsvRequester->getFilename();
        if ($this->checklastFilename($csvFilename)) {
            $this->addFlashMessage('tx_icebergmap_domain_model_icebergdata.new_csv_file_found', $this->getLastFilename(), ContextualFeedbackSeverity::OK);
        } else {
            $this->addFlashMessage('tx_icebergmap_domain_model_icebergdata.no_new_csv_file');
        }
        return Command::SUCCESS;
    }

    private function checklastFilename(string $csvFilename): bool
    {
        $lastFilename = $this->getLastFilename();
        if ($csvFilename == $lastFilename) {
            return false;
        }
        $this->setLastFilename($csvFilename);
        $this->setHasNewFile(true);
        return true;
    }
    
    private function getLastFilename()
    {
        return $this->registry->get(
            'icebergMap',
            'lastFilename',
        );
        
    }

    private function setLastFilename(string $csvFilename)
    {
        $this->registry->set('icebergMap', 'lastFilename', $csvFilename);
    }

    private function setHasNewFile(bool $value): void
    {
        $this->registry->set('icebergMap', 'hasNewFile', $value);
    }

    private function addFlashMessage(string $msgTag, $additionalInfo = '', $severity = ContextualFeedbackSeverity::INFO): void
    {
        echo "Ping (1728890096338): ". __LINE__ . "-" . __FILE__  . "<br>\n";
        $message = GeneralUtility::makeInstance(
            FlashMessage::class,
            $this->getTranslationService->translate($msgTag) . ' ' . $additionalInfo,
            '',
            $severity
        );
        $flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);
        $defaultFlashMessageQueue = $flashMessageService->getMessageQueueByIdentifier();
        $defaultFlashMessageQueue->enqueue($message);
    }


}