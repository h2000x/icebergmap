<?php

declare(strict_types=1);

namespace Justabunchof\Icebergmap\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Iceberg extends AbstractEntity
{
    /**
     * name
     */
    protected string $name = '';


    /**
     * firstappearance
     */
    protected \DateTime $firstappearance;

    /**
     * Returns the $name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Returns the $firstappearance
     */
    public function getFirstappearance(): \DateTime
    {
        return $this->firstappearance;
    }

    /**
     * Sets the firstappearance
     */
    public function setFirstappearance(\DateTime $firstappearance): void
    {
        $this->firstappearance = $firstappearance;
    }
}