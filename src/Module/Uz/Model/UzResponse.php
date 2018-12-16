<?php

namespace App\Module\Uz\Model;

class UzResponse implements UzResponseInterface
{
    /**
     * @var UzRequestInterface
     */
    private $uzRequest;
    /**
     * @var array
     */
    private $trains;
    /**
     * @var string
     */
    private $viewUrl;

    /**
     * @var int
     */
    private $createdAt;
    public function __construct(UzRequestInterface $uzRequest, array $trains, string $viewUrl, int $createdAt)
    {
        $this->uzRequest = $uzRequest;
        $this->trains = $trains;
        $this->viewUrl = $viewUrl;
        $this->createdAt = $createdAt;
    }

    /**
     * @return UzRequestInterface
     */
    public function getUzRequest(): UzRequestInterface
    {
        return $this->uzRequest;
    }

    public function getTrains(): array
    {
        return $this->trains;
    }

    /**
     * @return string
     */
    public function getViewUrl(): string
    {
        return $this->viewUrl;
    }

    /**
     * @return int
     */
    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }
}
