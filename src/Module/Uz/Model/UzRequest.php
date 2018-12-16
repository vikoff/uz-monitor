<?php

namespace App\Module\Uz\Model;

class UzRequest implements UzRequestInterface
{
    /**
     * @var string
     */
    private $stationCodeFrom;
    /**
     * @var string
     */
    private $stationCodeTo;
    /**
     * @var string
     */
    private $date;
    /**
     * @var string
     */
    private $time;

    /**
     * @param string $stationCodeFrom
     * @param string $stationCodeTo
     * @param string $date
     * @param string $time
     * @throws \Exception
     */
    public function __construct(
        string $stationCodeFrom,
        string $stationCodeTo,
        string $date,
        string $time = '00:00'
    ) {
        if ((new \DateTime($date))->format('Y-m-d') !== $date) {
            throw new \InvalidArgumentException('Invalid date format. Expecting Y-m-d');
        }

        $this->stationCodeFrom = $stationCodeFrom;
        $this->stationCodeTo = $stationCodeTo;
        $this->date = $date;
        $this->time = $time;
    }

    /**
     * @return string
     */
    public function getStationCodeFrom(): string
    {
        return $this->stationCodeFrom;
    }

    /**
     * @return string
     */
    public function getStationCodeTo(): string
    {
        return $this->stationCodeTo;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getTime(): string
    {
        return $this->time;
    }
}
