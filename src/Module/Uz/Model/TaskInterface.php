<?php

namespace App\Module\Uz\Model;

interface TaskInterface
{
    public function getId(): ?int;

    public function getCreatedAt(): ?string;

    public function getStationCodeFrom(): string;

    public function getStationCodeTo(): string;

    public function getDate(): string;

    public function getNotifyChannels(): array;

    public function getCheckInterval(): ?int;

    public function getNextAt(): ?int;

    public function isTestMode(): bool;

    public function withNextAt(int $nextAt): TaskInterface;
}
