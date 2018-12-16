<?php

namespace App\Module\Uz\Model;

interface NewPlacesInterface
{
    public function isTestMode(): bool;

    /**
     * @return NewPlacesEntityInterface[]
     */
    public function getEntities(): array;
}
