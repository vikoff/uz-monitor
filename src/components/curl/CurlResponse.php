<?php

namespace app\components\curl;

class CurlResponse
{
	/**
	 * @var resource
	 */
	private $curl;
	private $body;
	private $headers;
	private $requestBody;

	private $curlInfo;
	private $requestMethod;

	/**
	 * CurlResponse constructor.
	 * @param resource $curl
	 * @param string $body
	 * @param string $headers
	 * @param string $requestMethod
	 * @param array|string $requestBody
	 */
	public function __construct($curl, $body, $headers, $requestMethod, $requestBody)
	{
		$this->curl = $curl;
		$this->body = $body;
		$this->headers = $headers;
		$this->requestMethod = $requestMethod;
		$this->requestBody = $requestBody;
	}

	/**
	 * @return string
	 */
	public function getBody()
	{
		return $this->body;
	}

	public function getBodyFormatted()
	{
		$arr = json_decode($this->body, true);
		return $arr ? json_encode($arr, JSON_PRETTY_PRINT) : '';
	}

	public function getInfo()
	{
		if ($this->curlInfo === null) {
			$this->curlInfo = curl_getinfo($this->curl);
		}
		return $this->curlInfo;
	}

    /**
     * @return string
     */
	public function getError()
	{
		return curl_error($this->curl);
	}

	public function getResponseHeadersStr()
	{
		return $this->headers;
	}

	public function getHttpCode()
	{
		return $this->getInfo()['http_code'];
	}

	public function getUrl()
	{
		return $this->getInfo()['url'];
	}

	/**
	 * @return string
	 */
	public function getRequestMethod()
	{
		return $this->requestMethod;
	}

	public function getRequestBody()
	{
		return $this->requestBody;
	}

    /**
     * @return $this
     * @throws \Exception
     */
    public function ensureStatus200()
    {
        $httpCode = $this->getHttpCode();
        if ($httpCode !== 200) {
            throw new \Exception("Expected http-code 200, got $httpCode\n\n" . $this->body);
        }

        return $this;
	}

    /**
     * @noinspection PhpDocSignatureInspection
     * @return array
     * @throws \Exception
     */
    public function getParsedBody()
    {
        throw new \Exception('Method getParsedBody not implemented for ' . __CLASS__);
	}

    /**
     * @param $fieldName
     * @param bool $throwOnFail
     * @return mixed|null
     * @throws \Exception
     */
    public function getField(/** @noinspection PhpUnusedParameterInspection */ $fieldName, $throwOnFail = false)
    {
        throw new \Exception('Method getField not implemented for ' . __CLASS__);
	}
}
