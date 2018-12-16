<?php

namespace App\Module\Uz\Component;

use app\components\curl\CurlRequest;
use App\Module\Uz\Model\UzRequestInterface;
use App\Module\Uz\Model\UzResponse;
use App\Module\Uz\Model\UzResponseInterface;

class UzApi implements UzApiInterface
{
    /**
     * @param UzRequestInterface $uzRequest
     * @return UzResponseInterface
     * @throws \Exception
     */
    public function getTrains(UzRequestInterface $uzRequest): UzResponseInterface
    {
        return $this->doRequest($uzRequest);
    }

    /**
     * @param UzRequestInterface $uzRequest
     * @return UzResponseInterface
     * @throws \Exception
     */
    private function doRequest(UzRequestInterface $uzRequest): UzResponseInterface
    {
        $response = CurlRequest::init('https://booking.uz.gov.ua/ru/train_search/')
            ->setPostFields([
                'from' => $uzRequest->getStationCodeFrom(),
                'to' => $uzRequest->getStationCodeTo(),
                'date' => $uzRequest->getDate(),
                'time' => $uzRequest->getTime(),
            ])
            ->allowOnly2xx()
            ->exec();

        $data = json_decode($response->getBody(), true);

        if (!isset($data['data']['list'])) {
            throw new \Exception('Invalid response. ' . print_r($response, true));
        }

        return new UzResponse(
            $uzRequest,
            $data['data']['list'],
            $this->buildViewUrl($uzRequest),
            time()
        );
    }

    /**
     * @param UzRequestInterface $uzRequest
     * @return string
     */
    public function buildViewUrl(UzRequestInterface $uzRequest): string
    {
//        'https://booking.uz.gov.ua/ru/?from=2200001&to=2218200&date=2018-09-28&time=00%3A00&url=train-list';

        return 'https://booking.uz.gov.ua/ru/?'
            . http_build_query([
                'from' => $uzRequest->getStationCodeFrom(),
                'to' => $uzRequest->getStationCodeTo(),
                'date' => $uzRequest->getDate(),
                'time' => $uzRequest->getTime(),
                'url' => 'train-list',
            ]);
    }
}
