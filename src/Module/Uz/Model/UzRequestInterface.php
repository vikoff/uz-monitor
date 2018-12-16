<?php

namespace App\Module\Uz\Model;

interface UzRequestInterface
{
    public function getStationCodeFrom(): string;

    public function getStationCodeTo(): string;

    public function getDate(): string;

    public function getTime(): string;
}
