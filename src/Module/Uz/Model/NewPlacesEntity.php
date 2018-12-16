<?php

namespace App\Module\Uz\Model;

class NewPlacesEntity implements NewPlacesEntityInterface
{
    /**
     * @var string
     */
    private $train;
    /**
     * @var string
     */
    private $placeType;
    /**
     * @var int
     */
    private $count;
    /**
     * @var int
     */
    private $prevCount;

    public function __construct(
        string $train,
        string $placeType,
        int $count,
        int $prevCount
    ) {
        $this->train = $train;
        $this->placeType = $placeType;
        $this->count = $count;
        $this->prevCount = $prevCount;
    }

    /**
     * @return string
     */
    public function getTrain(): string
    {
        return $this->train;
    }

    /**
     * @return string
     */
    public function getPlaceType(): string
    {
        return $this->placeType;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @return int
     */
    public function getPrevCount(): int
    {
        return $this->prevCount;
    }
}
