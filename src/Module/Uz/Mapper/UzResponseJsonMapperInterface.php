<?php

namespace App\Module\Uz\Mapper;

use App\Module\Uz\Model\UzResponseInterface;

interface UzResponseJsonMapperInterface
{
    public function toJson(UzResponseInterface $uzResponse): string;

    public function fromJson(string $json): UzResponseInterface;
}
