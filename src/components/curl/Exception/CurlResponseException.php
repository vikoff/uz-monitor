<?php

namespace App\components\curl\Exception;

use app\components\curl\CurlResponse;

class CurlResponseException extends CurlException
{
    /**
     * @var CurlResponse
     */
    private $response;

    public function __construct(CurlResponse $response, $message = '', $code = 0)
    {
        $this->response = $response;
        parent::__construct($message, $code);
    }
}
