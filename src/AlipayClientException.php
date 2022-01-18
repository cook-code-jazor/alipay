<?php

namespace Jazor\AliPay;

use Throwable;

class AlipayClientException extends \Exception
{
    private ?array $response;
    private ?array $requestParams = null;
    private string $requestUrl = '';

    public function __construct($message = "", ?array $response = null, ?array $requestParams = null, string $requestUrl = '')
    {
        parent::__construct($message, 0, null);
        $this->response = $response;
        $this->requestParams = $requestParams;
        $this->requestUrl = $requestUrl;
    }

    /**
     * @return array|null
     */
    public function getResponse(): ?array
    {
        return $this->response;
    }

    /**
     * @return array|null
     */
    public function getRequestParams(): ?array
    {
        return $this->requestParams;
    }

    /**
     * @param array|null $requestParams
     */
    public function setRequestParams(?array $requestParams): void
    {
        $this->requestParams = $requestParams;
    }

    /**
     * @return string|null
     */
    public function getRequestUrl(): ?string
    {
        return $this->requestUrl;
    }

    /**
     * @param string $requestUrl
     */
    public function setRequestUrl(string $requestUrl): void
    {
        $this->requestUrl = $requestUrl;
    }
}

