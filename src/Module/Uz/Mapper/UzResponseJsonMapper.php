<?php

namespace App\Module\Uz\Mapper;

use App\Module\Uz\Model\UzRequest;
use App\Module\Uz\Model\UzResponse;
use App\Module\Uz\Model\UzResponseInterface;

class UzResponseJsonMapper implements UzResponseJsonMapperInterface
{
    /**
     * @param string $json
     * @return UzResponseInterface
     * @throws \Exception
     */
    public function fromJson(string $json): UzResponseInterface
    {
        $data = json_decode($json);
        $request = $data['request'];

        new UzResponse(
            new UzRequest(
                $request['stationFromCode'],
                $request['stationToCode'],
                $request['date'],
                $request['time']
            ),
            $data['trains'],
            $data['viewUrl'],
            $data['createdAt']
        );
    }

    public function toJson(UzResponseInterface $uzResponse): string
    {
        $request = $uzResponse->getUzRequest();

        return json_encode([
            'request' => [
                'stationFromCode' => $request->getStationCodeFrom(),
                'stationToCode' => $request->getStationCodeTo(),
                'date' => $request->getDate(),
                'time' => $request->getTime(),
            ],
            'trains' => $uzResponse->getTrains(),
            'viewUrl' => $uzResponse->getTrains(),
            'createdAt' => $uzResponse->getCreatedAt(),
        ]);
    }
}
