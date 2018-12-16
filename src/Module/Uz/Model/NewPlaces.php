<?php

namespace App\Module\Uz\Model;

class NewPlaces implements NewPlacesInterface
{
    /**
     * @var array
     */
    private $entities;
    /**
     * @var bool
     */
    private $testMode;

    public function __construct(
        array $entities,
        bool $testMode
    ) {
        $this->entities = $entities;
        $this->testMode = $testMode;
    }

    /**
     * @return NewPlacesEntityInterface[]
     */
    public function getEntities(): array
    {
        return $this->entities;
    }

    public function isTestMode(): bool
    {
        return $this->testMode;
    }
}
