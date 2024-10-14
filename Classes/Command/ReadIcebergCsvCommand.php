<?php

declare(strict_types=1);

namespace Justabunchof\Icebergmap\Command;

use Justabunchof\Icebergmap\Service\CheckAndReadCsvService;
use Justabunchof\Icebergmap\Domain\Model\Iceberg;
use Justabunchof\Icebergmap\Domain\Model\IcebergData;
use Justabunchof\Icebergmap\Domain\Repository\IcebergRepository;
use Justabunchof\Icebergmap\Domain\Repository\IcebergDataRepository;
use Justabunchof\Icebergmap\Service\KmlFileService;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Registry;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

class ReadIcebergCsvCommand extends Command
{
//    protected CheckAndReadCsvService $checkAndReadCsvService;

    public function __construct(
        private CheckAndReadCsvService $checkAndReadCsvService,
        private readonly Registry $registry,
        private KmlFileService $kmlFileService,
        private IcebergRepository $icebergRepository,
        private IcebergDataRepository $icebergDataRepository,
        private PersistenceManager $persistenceManager,
        string                            $name = null
    )
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Checks if there is a new Iceberg CSV, if true downloads it and write it\'s data in the database.')
            ->addOption(
                'path-name',
                '-p',
                InputOption::VALUE_REQUIRED,
                'Diretory for the csv-files',
            );;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Do awesome stuff
        $path_name = $input->getOption('path-name');
        if (empty($path_name)) {
            echo "Ping (1728921078323): ". __LINE__ . "-" . __FILE__  . "<br>\n";
            if (!$this->hasNewCsvFile()) {
                return Command::SUCCESS;
            }
            $csvString = $this->registry->get('icebergMap','lastFileContent');
            $csvArray = explode("\n", $csvString);
            $ok = $this->saveData($csvArray);
            if ($ok) {
                $this->registry->set('icebergMap', 'hasNewFile', false);
            }

        } else {
            //Liest aus einem Directory
            $csvFiles = $this->checkAndReadCsvService->readCsvFiles();
            if (count($csvFiles) > 0) {
                foreach ($csvFiles as $single_csv_file) {
//                $csvArray = $this->checkAndReadCsvService->readCsvFileFromDir($single_csv_file);
//                $this->saveData($csvArray);
                }
            }
        }

        return Command::SUCCESS;
    }

    private function transformCsvData($single_row)
    {
        $result = [];
        switch (count($single_row)) {
            case 7:
                $result['sqkm'] = 0;
                $result['date'] = $single_row[6];
                break;
            case 9:
                $result['sqkm'] = $single_row[7];
                $result['date'] = $single_row[8];
                break;

            default:
//                print_r($single_row);
                return  false;
        }
        $result['name'] = $single_row[0];
        $result['length'] = $single_row[1];
        $result['width'] = $single_row[2];
        $result['latitude'] = $single_row[3];
        $result['longitude'] = $single_row[4];
        return $result;
    }
    private function  saveData(bool|array $csvArray): bool
    {
        /**
         * Datastructure
         *
         * Array
         * (
         * [0] => Iceberg
         * [1] => Length (NM)
         * [2] => Width (NM)
         * [3] => Latitude
         * [4] => Longitude
         * [5] => Area (sqMI)
         * [6] => Area (sqNM)
         * [7] => Area (sqKM)
         * [8] => Last Update
         * )
         *
         * and
         *
         * Array
         * (
         * [0] => Iceberg
         * [1] => Length (NM)
         * [2] => Width (NM)
         * [3] => Latitude
         * [4] => Longitude
         * [5] => Remarks
         * [6] => Last Update
         * )
         */
        $null = array_shift($csvArray);

        
        foreach ($csvArray as $single_row) {
            if (gettype($single_row) == 'string') {
                $single_row = explode(',', $single_row);
            }
            //TODO: Das muss abhängig von der ersten Zeile der CSV-Datei gemacht werden
            $single_data = $this->transformCsvData($single_row);
            if(!$single_data) {
                continue;
            }
            $name = $single_data['name'];
            $csvDate = $single_data['date'];
            $result = $this->icebergRepository->findByName($name);
            if ($result->count() > 0) {
                // Update
                $iceberg = $result->getFirst();
                $icebergData = $this->createIcebergDataObj($iceberg, $single_data);

                $dateObj = $this->getDateObj($single_data['date']);
                if ($this->icebergDataRepository->checkIfDataIsAlreadyInDb($iceberg, $dateObj)) {
                    continue;
                }
                $this->icebergDataRepository->add($icebergData);
                $this->persistenceManager->persistAll();
            } else {
                // New
                $ts = strtotime($csvDate);
                $dt = new \DateTime();
                $dt->setTimestamp($ts);
                $dt->modify('+3 hours');

                $iceberg = new Iceberg();
                $iceberg->setName($name);
                $iceberg->setFirstappearance($dt);

                $this->icebergRepository->add($iceberg);
                $icebergData = $this->createIcebergDataObj($iceberg, $single_data);
                $this->icebergDataRepository->add($icebergData);

                $this->persistenceManager->persistAll();
            }
        }
        return true;
    }

    private function createIcebergDataObj(Iceberg $iceberg, array $single_date): IcebergData
    {
        $dateObj = $this->getDateObj($single_date['date']);

        $icebergData = new IcebergData();
        $icebergData->setIceberg($iceberg);
        $icebergData->setLatitude($single_date['latitude']);
        $icebergData->setLongitude($single_date['longitude']);
        $icebergData->setSquarekm($single_date['sqkm']);
        $icebergData->setLength($single_date['length']);
        $icebergData->setWidth($single_date['width']);
        $icebergData->setDatadate($dateObj);

        return $icebergData;

    }

    private function getDateObj(string $date)
    {
        $ts = strtotime($date);
        $dt = new \DateTime();
        $dt->setTimestamp($ts);
        return $dt->modify('+3 hours');
    }

    private function hasNewCsvFile()
    {
        return $this->registry->get('icebergMap', 'hasNewFile');
    }
}
