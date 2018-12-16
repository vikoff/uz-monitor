<?php

namespace App\Module\Uz\Model;

class Task implements TaskInterface
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
     * @var array
     */
    private $notifyChannels;
    /**
     * @var int|null
     */
    private $nextAt;
    /**
     * @var bool
     */
    private $testMode;
    /**
     * @var int|null
     */
    private $checkInterval;
    /**
     * @var int|null
     */
    private $id;
    /**
     * @var string|null
     */
    private $createdAt;

    public function __construct(
        string $stationCodeFrom,
        string $stationCodeTo,
        string $date,
        array $notifyChannels,
        ?int $nextAt,
        bool $testMode,
        ?int $checkInterval,
        ?int $id,
        ?string $createdAt
    ) {
        $this->stationCodeFrom = $stationCodeFrom;
        $this->stationCodeTo = $stationCodeTo;
        $this->date = $date;
        $this->notifyChannels = $notifyChannels;
        $this->nextAt = $nextAt;
        $this->testMode = $testMode;
        $this->checkInterval = $checkInterval;
        $this->id = $id;
        $this->createdAt = $createdAt;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
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
     * @return array
     */
    public function getNotifyChannels(): array
    {
        return $this->notifyChannels;
    }

    /**
     * @return int|null
     */
    public function getNextAt(): ?int
    {
        return $this->nextAt;
    }

    /**
     * @return bool
     */
    public function isTestMode(): bool
    {
        return $this->testMode;
    }

    public function getCheckInterval(): ?int
    {
        return $this->checkInterval;
    }

    public function withNextAt(int $nextAt): TaskInterface
    {
        $clone = clone $this;
        $clone->nextAt = $nextAt;
        return $clone;
    }
}
