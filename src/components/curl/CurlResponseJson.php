<?php

namespace app\components\curl;

class CurlResponseJson extends CurlResponse
{
    private $parsedBody;

    /**
     * @param bool $throwOnFail
     * @return array
     * @throws \Exception
     */
    public function getParsedBody($throwOnFail = false)
    {
        if ($this->parsedBody === null) {
            $arr = json_decode($this->getBody(), true);
            if (!is_array($arr)) {
                if ($throwOnFail) {
                    throw new \Exception('Malformed json: ' . $this->getBody());
                } else {
                    $arr = [];
                }
            }
            $this->parsedBody = $arr;
        }

        return $this->parsedBody;
    }

    /**
     * @param $fieldName
     * @param bool $throwOnFail
     * @return mixed|null
     * @throws \Exception
     */
    public function getField($fieldName, $throwOnFail = false)
    {
        $json = $this->getParsedBody();
        if (!isset($json[$fieldName])) {
            if ($throwOnFail) {
                throw new \Exception("Field $fieldName is not set");
            } else {
                return null;
            }
        }

        return $json[$fieldName];
    }
}
