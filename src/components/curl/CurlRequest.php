<?php

namespace app\components\curl;

use App\components\curl\Exception\CurlResponseException;

class CurlRequest
{
    const RESPONSE_DEFAULT = 'default';
    const RESPONSE_JSON = 'json';

	private $curl;
	private $url;

	private $contentType;
	private $requestBody;
	private $requestMethod = 'GET';
	private $headers = [];
	private $allowOnly2xx = false;

    /**
     * @var string
     */
	private $responseFormat = self::RESPONSE_DEFAULT;

    /**
     * @param string $url
     * @return CurlRequest
     */
	public static function init($url)
	{
		return new static($url);
	}

	private function __construct($url)
	{
		$this->url = $url;
		$this->curl = curl_init($this->url);

		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
	}

	public function setMethodPost()
	{
		curl_setopt($this->curl, CURLOPT_POST, true);
		$this->requestMethod = 'POST';
		return $this;
	}

	public function setMethodPut()
	{
		curl_setopt($this->curl, CURLOPT_PUT, true);
		$this->requestMethod = 'PUT';
		return $this;
	}

	/**
	 * Set POST body and method=POST
	 * @param string|array $data
	 * @return $this
	 */
	public function setPostFields($data)
	{
		$this->setMethodPost();
        $dataStr = is_array($data) ? http_build_query($data) : $data;
		$this->requestBody = $dataStr;
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $dataStr);
		return $this;
	}

    /**
     * Set POST body, method=POST and set "Content-type: application/json" header
     * @param string|array $data
     * @return $this
     * @throws \Exception
     */
	public function setJson($data)
	{
		$data = is_string($data) ? $data : json_encode($data);
		$this->setPostFields($data);
		$this->setContentTypeJson();
		return $this;
	}

	/**
	 * Set "Content-type: application/json" header
	 * @return $this
	 * @throws \Exception
	 */
	public function setContentTypeJson()
	{
		if (!empty($this->contentType)) {
			throw new \Exception('Content-type already set');
		}
		$this->contentType = 'application/json';
		$this->addHeader('Content-type: application/json');
		return $this;
	}

    /**
     * @example
     *     setBasicAuth('user', 'password');
     *     setBasicAuth('user:password');
     *
     * @param string $loginOrAll
     * @param string null $password
     * @return $this
     */
	public function setBasicAuth($loginOrAll, $password = null)
	{
	    $authStr = $password === null ? $loginOrAll : "$loginOrAll:$password";

		curl_setopt($this->curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ;
		curl_setopt($this->curl, CURLOPT_USERPWD, $authStr);

		return $this;
	}

	public function addHeaders(array $headers)
	{
	    $this->headers = array_merge($this->headers, $headers);
		return $this;
	}

    public function addHeader($header)
    {
        $this->headers[] = $header;
        return $this;
	}

    /**
     * @param int $key
     * @param mixed $value
     * @return $this
     */
    public function setCurlOpt($key, $value)
	{
        curl_setopt($this->curl, $key, $value);
        return $this;
	}

    /**
     * @return $this
     */
    public function expectJson()
    {
        $this->responseFormat = self::RESPONSE_JSON;
        return $this;
	}

    public function allowOnly2xx()
    {
        $this->allowOnly2xx = true;
        return $this;
	}

    /**
     * @return CurlResponse
     * @throws \Exception
     */
	public function exec()
	{
	    if (count($this->headers) > 0) {
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->headers);
        }

		curl_setopt($this->curl, CURLOPT_HEADER, true);
		curl_setopt($this->curl, CURLINFO_HEADER_OUT, true);

		$response = curl_exec($this->curl);

		$info = curl_getinfo($this->curl);
		if (!empty($info['header_size'])) {
			$headers = mb_substr($response, 0, $info['header_size']);
			$body = mb_substr($response, $info['header_size']);
		} else {
			list($headers, $body) = explode("\r\n\r\n", $response, 2) + ['', ''];
		}

        if ($this->allowOnly2xx && "{$info['http_code']}"[0] !== 2) {
            throw new CurlResponseException(
                $this->buildDefaultResponse($body, $headers),
                'Unexpected http code ' . $info['http_code'],
                (int)$info['http_code']
            );
        }

		switch ($this->responseFormat) {
            case self::RESPONSE_DEFAULT:
                return $this->buildDefaultResponse($body, $headers);

            case self::RESPONSE_JSON:
                return new CurlResponseJson($this->curl, $body, $headers, $this->requestMethod, $this->requestBody);

            default:
                throw new \Exception("invalid expected response format $this->responseFormat");
        }
	}

    public function buildDefaultResponse($body, $headers)
    {
        return new CurlResponse($this->curl, $body, $headers, $this->requestMethod, $this->requestBody);
	}
}
