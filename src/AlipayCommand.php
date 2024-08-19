<?php
namespace Jazor\AliPay;


use Jazor\Console;

class AlipayCommand implements \ArrayAccess
{
    protected string $method = '';
    protected string $responseKey = '';

    protected array $bizContent = [];

    protected bool $responseAsUrl = false;

    public function getRequestParams(){
        return [
            'biz_content' => $this->toJson()
        ];
    }

    function __get($name)
    {
        return $this->bizContent[$name] ?? null;
    }

    function __set($name, $value)
    {
        $this->bizContent[$name] = $value;
    }

    public function offsetSet($offset, $value)
    {
        $this->bizContent[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->bizContent[$offset]);
    }

    public function offsetExists($offset)
    {
        return isset($this->bizContent[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->bizContent[$offset] ?? null;
    }

    public function getBizContent()
    {
        return $this->bizContent;
    }

    public function toJson()
    {
        return json_encode($this->bizContent, 256);
    }

    /**
     * @return string
     */
    public function getResponseKey(): string
    {
        if(empty($this->responseKey)){
            $name = static::class;
            $idx = strrpos($name, '\\');
            if($idx !== false) $name = substr($name, $idx + 1);
            $name = preg_replace('/(.)(?=[A-Z])/u', '$1_', $name);
            $name = strtolower($name);
            return $name . '_response';
        }
        return $this->responseKey;
    }
    public function getResponse($response)
    {
        return $response;
    }
    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed|void
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        if(!(strpos($name, 'set') === 0 || strpos($name, 'get') === 0)) {
            throw new \Exception('unknown method');
        }

        $method = substr($name, 0, 3);

        $name = substr($name, 3);

        $name = preg_replace('/(.)(?=[A-Z])/u', '$1_', $name);
        $name = strtolower($name);

        if($method === 'set') {
            $this->bizContent[$name] = $arguments[0];
            return;
        }
        return $this->bizContent[$name];
    }

    /**
     * @return bool
     */
    public function isResponseAsUrl(): bool
    {
        return $this->responseAsUrl;
    }
}

