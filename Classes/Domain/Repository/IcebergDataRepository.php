<?php

declare(strict_types=1);

namespace Justabunchof\Icebergmap\Domain\Repository;

use Justabunchof\Icebergmap\Domain\Model\Iceberg;
use Justabunchof\Icebergmap\Domain\Model\IcebergData;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Extbase\Persistence\Repository;
use Justabunchof\Zbtypo3\Domain\Model\Files;
use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;


/**
 * This file is part of the "fileFolderTest" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2021 Jaust a Bunch of <info@ahbece.xyz>, Justabunchof
 */

/**
 * The repository for Files
 */
class IcebergDataRepository extends Repository
{
    protected string $tableName;

    /**
     * typoscript settings.
     */
    protected array $settings;

    protected $queryBuilder;

    protected DataMapper $dataMapper;

    protected ConfigurationManager $configurationManager;

    public function initializeObject(
        DataMapper $dataMapper,
        ConfigurationManager $configurationManager
    ): void
    {
        $this->dataMapper = $dataMapper;
        $this->configurationManager = $configurationManager;

        $className = IcebergData::class;
        $this->tableName = $dataMapper->getDataMap($className)->getTableName();

        $this->queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable($this->tableName);


//        $this->settings = $configurationManager->getConfiguration(
//            ConfigurationManager::CONFIGURATION_TYPE_SETTINGS,
//            'Zbtypo3'
//        );
    }

    public function checkIfDataIsAlreadyInDb($iceberg, $date): bool
    {
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $queryBuilder = $connectionPool->getQueryBuilderForTable('tx_icebergmap_domain_model_iceberg');
        $result = $queryBuilder
            ->select('tx_icebergmap_domain_model_iceberg.uid')
            ->from('tx_icebergmap_domain_model_iceberg')
            ->join(
                'tx_icebergmap_domain_model_iceberg',
                'tx_icebergmap_domain_model_icebergdata',
                'id',
                $queryBuilder->expr()->eq('id.iceberg', $queryBuilder->quoteIdentifier('tx_icebergmap_domain_model_iceberg.uid'))
            )
            ->where(
                $queryBuilder->expr()->eq('id.iceberg', $queryBuilder->createNamedParameter($iceberg->getUid(), Connection::PARAM_INT)),
                $queryBuilder->expr()->eq('id.datadate', $queryBuilder->createNamedParameter($date->getTimestamp(), Connection::PARAM_INT))
            )
            ->executeQuery();
        if ($result->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
         
        
//            ->orderBy('event.top_of_list', 'DESC')
//            ->addOrderBy('day.sort_day_time', 'ASC')
//            ->addOrderBy('day.day_time', 'ASC');

    }

    public function findDataSortByDate(Iceberg $iceberg)
    {
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $queryBuilder = $connectionPool->getQueryBuilderForTable($this->tableName);
        
        $result = $queryBuilder
            ->select('*')
            ->from($this->tableName)
            ->where(
                $queryBuilder->expr()->eq('iceberg', $queryBuilder->createNamedParameter($iceberg->getUid(), Connection::PARAM_INT))
            )
            ->orderBy('datadate', 'ASC')
            ->executeQuery();

        if ($result->rowCount() == 0) {
            return false;
        }

        return $result->fetchAllAssociativeIndexed();
    }

    public function countDatadates()
    {
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $queryBuilder = $connectionPool->getQueryBuilderForTable($this->tableName);
        $result = $queryBuilder
            ->select('datadate')
            ->from($this->tableName)
            ->groupBy('datadate')
            ->executeQuery();

        return $result->rowCount();
    }
}
