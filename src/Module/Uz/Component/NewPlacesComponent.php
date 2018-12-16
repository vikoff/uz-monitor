<?php

namespace App\Module\Uz\Component;

use App\Module\Uz\Model\NewPlaces;
use App\Module\Uz\Model\NewPlacesEntity;
use App\Module\Uz\Model\NewPlacesInterface;
use App\Module\Uz\Model\TaskInterface;
use App\Module\Uz\Model\UzResponseInterface;

class NewPlacesComponent implements NewPlacesComponentInterface
{
    public function checkPlaces(
        TaskInterface $task,
        UzResponseInterface $uzResponse,
        UzResponseInterface $prevUzResponse
    ): ?NewPlacesInterface {
        $newPlaces = [];
        $prevTrains = $prevUzResponse->getTrains();

        foreach ($uzResponse->getTrains() as $train => $trainData) {
            foreach ($trainData['types'] as $type) {
                $placeType = $type['title'];
                if (!isset($prevTrains[$train]['types'][$placeType])
                    || $prevTrains[$train]['types'][$placeType]['places'] < $type['places']
                ) {
                    $newPlaces[] = new NewPlacesEntity(
                        $train,
                        $placeType,
                        $type['places'],
                        $prevTrains[$train]['types'][$placeType]['places']
                    );
                }
            }
        }

        if (count($newPlaces) > 0) {
            return new NewPlaces($newPlaces, $task->isTestMode());
        } else {
            return null;
        }
    }
}
