<?php

declare(strict_types=1);

namespace Justabunchof\Icebergmap\Domain\Model;

use Justabunchof\Icebergmap\Domain\Model\Iceberg;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class IcebergData extends AbstractEntity
{

    public ?Iceberg $iceberg = null;

    protected float $latitude = 0.0;

    protected float $longitude = 0.0;

    protected float $length = 0.0;

    protected float $width = 0.0;

    protected float $squarekm = 0.0;

    protected \DateTime $datadate;


    /**
     * Adds a iceberg to the data
     */
    public function addIceberg(Iceberg $iceberg): void
    {
        echo $iceberg->getName()  . "<br>\n";

        $this->iceberg?->attach($iceberg);
    }

    /**
     * Remove a iceberg from the data
     */
    public function removeIceberg(Iceberg $icebergToRemove): void
    {
        $this->iceberg?->detach($icebergToRemove);
    }

    /**
     * Returns the iceberg in this data
     *
     * @return ObjectStorage<Iceberg>
     */
    public function getIceberg(): ObjectStorage
    {
        return $this->iceberg;
    }


    public function setIceberg(Iceberg $iceberg): void
    {
        $this->iceberg = $iceberg;
    }

    /**
     * firstappearance
     */
    protected \DateTime $firstappearance;

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function setLatitude($latitude): void
    {
        $this->latitude = (float)$latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function setLongitude($longitude): void
    {
        $this->longitude = (float)$longitude;
    }

    public function getLength(): float
    {
        return $this->length;
    }

    public function setLength($length): void
    {
        $this->length = (float)$length;
    }

    public function getWidth(): float
    {
        return $this->width;
    }

    public function setWidth($width): void
    {
        $this->width = (float)$width;
    }

    public function getSquarekm(): float
    {
        return $this->squarekm;
    }

    public function setSquarekm($squarekm): void
    {
        $this->squarekm = (float)$squarekm;
    }
    /**
     * Get the datadate
     */
    public function getDatadate(): \DateTime
    {
        return $this->datadate;
    }

    /**
     * Set the datadate
     */
    public function setDatadate(\DateTime $datadate): void
    {
        $this->datadate = $datadate;
    }
}