<?php

namespace App\Module\Uz\Model;

interface NewPlacesEntityInterface
{
    public function getTrain(): string;

    public function getPlaceType(): string;

    public function getCount(): int;

    public function getPrevCount(): int;
}
