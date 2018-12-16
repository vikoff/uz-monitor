<?php

namespace App\Module\Uz\Component;

use App\Module\Uz\Model\UzRequestInterface;
use App\Module\Uz\Model\UzResponseInterface;

interface UzApiInterface
{
    public function getTrains(UzRequestInterface $uzRequest): UzResponseInterface;
}
