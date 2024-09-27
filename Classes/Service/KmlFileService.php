<?php

namespace Justabunchof\Icebergmap\Service;

use Justabunchof\Icebergmap\Domain\Model\Iceberg;
use Justabunchof\Icebergmap\Domain\Repository\IcebergDataRepository;
use Justabunchof\Icebergmap\Domain\Repository\IcebergRepository;
use JWeiland\Events2\Configuration\ExtConf;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class KmlFileService
{

    private array $extConf;

    private string $data_path;

    private string $iceberg_placemarks;

    private string $default_line_style = 'defLineStyle';

    public function __construct(
        private IcebergRepository $icebergRepository,
        private IcebergDataRepository $icebergDataRepository,
        private ExtensionConfiguration $extensionConfiguration
    )
    {
        $this->extConf = $this->extensionConfiguration->get('icebergmap');

        $this->extensionKey = GeneralUtility::camelCaseToLowerCaseUnderscored(explode('\\', $this::class )[1] );
        $varPath = Environment::getVarPath();
        $this->data_path = $varPath . '/' . strtolower($this->extensionKey) . '/';
    }
    
    public function createKmlFile()
    {

        $kml_base_file = GeneralUtility::getFileAbsFileName('EXT:icebergmap/Resources/Private/Xml/Base.kml');
        $kml_file = file_get_contents($kml_base_file);

        $str_styles = $this->getKmlStyles();

        $icebergs = $this->icebergRepository->findAll();
        $result = $icebergs->toArray();
        $placemarks_all = '';
        foreach ($result as $single_iceberg) {
            $placemarks_all .= $this->getDataForIceberg($single_iceberg);
        }
        $kml_file = str_replace(['%TITLE%','%PLACEMARK%','%STYLES'], [$this->extConf['title'],$placemarks_all], $str_styles, $kml_file);
        file_put_contents($this->data_path . 'iceberg.kml', $kml_file);
    }

    private function getDataForIceberg(Iceberg $iceberg): string
    {

        $kml_placemark_file = GeneralUtility::getFileAbsFileName('EXT:icebergmap/Resources/Private/Xml/Line.kml');
        $kml_placemark = file_get_contents($kml_placemark_file);
        $single_iceberg_data = $this->icebergDataRepository->findDataSortByDate($iceberg);

        $coordinates = '';
        foreach ($single_iceberg_data as $single_data_point) {
            $coordinates .= $single_data_point['longitude'] . ', ' . $single_data_point['latitude'] . ',0' . "\n";

        }

        return str_replace(
            ['%NAME%','%DESCRIPTION%','%STYLE%','%COORDINATES%'],
            [$iceberg->getName(),'', $this->default_line_style ,$coordinates],
            $kml_placemark
        );
    }

    private function getKmlStyles(): string
    {
        $kml_base_file = GeneralUtility::getFileAbsFileName('EXT:icebergmap/Resources/Private/Xml/Styles.kml');
        return file_get_contents($kml_base_file);
    }

}