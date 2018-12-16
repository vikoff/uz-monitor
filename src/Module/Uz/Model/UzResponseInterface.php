<?php

namespace App\Module\Uz\Model;

interface UzResponseInterface
{
    /**
     * @return UzRequestInterface
     * TODO возможно он не нужен
     */
    public function getUzRequest(): UzRequestInterface;

    /**
     * @return array
     */
    public function getTrains(): array;

    /**
     * @return int
     */
    public function getCreatedAt(): int;
}
