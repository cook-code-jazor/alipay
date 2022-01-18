<?php

namespace Jazor\AliPay;

use Jazor\Http\Request;
use Jazor\Http\Upload\UploadRequest;

/**
 * Class AlipayOpenApiClient
 * @package Jazor\AliPay
 */
class AlipayOpenApiClient
{
    private string $returnUrl = '';
    private string $notifyUrl = '';

    private array $basicConfig = [
        'app_id' => '',
        'method' => '',
        'format' => 'JSON',
        'return_url' => null,
        'charset' => 'utf-8',
        'sign_type' => 'RSA2',
        'sign' => '',
        'timestamp' => '',
        'version' => '1.0',
        'notify_url' => null,
        'app_auth_token' => null
    ];

    private string $api = "https://openapi.alipay.com/gateway.do";

    private string $userPrivateKey = '';
    private string $alipayPublicKey = '';


    /**
     * @return string
     */
    public function getReturnUrl(): string
    {
        return $this->returnUrl;
    }

    /**
     * @return string
     */
    public function getNotifyUrl(): string
    {
        return $this->notifyUrl;
    }

    /**
     * @param null $value pem format
     * @return string|void
     * @throws \Exception
     */
	public function publicKey($value = null){
	    if($value === null) return $this->alipayPublicKey;
	    if(!self::isPemRawData($value[0])){
	        if(!is_file($value)){
                throw new \Exception('public key file not exists');
            }
            $value = file_get_contents($value);
        }
        $this->alipayPublicKey = self::getKeyData($value);
    }

    /**
     * @param null $value pem format, pkcs8
     * @return string|void
     * @throws \Exception
     */
    public function privateKey($value = null){
        if($value === null) return $this->userPrivateKey;
        if(!self::isPemRawData($value[0])){
            if(!is_file($value)){
                throw new \Exception('private key file not exists');
            }
            $value = file_get_contents($value);
        }
        $this->userPrivateKey = self::getKeyData($value, 'PRIVATE');
    }

    /**
     * 支付宝OpenApi核心
     * @param string $appId
     */
    public function __construct($appId)
    {
        $this->basicConfig['app_id'] = $appId;
    }

    /**
     * @param AlipayCommand $command
     * @param $requestParams
     * @return array
     * @throws AlipayClientException
     */
    private function getParams(AlipayCommand $command, &$requestParams){
        $requestArray = $this->basicConfig;
        $requestArray['method'] = $command->getMethod();
        $requestArray['timestamp'] = date('Y-m-d H:i:s');
        $requestArray['notify_url'] = $this->notifyUrl;
        $requestArray['return_url'] = $this->returnUrl;

        $systemParams = $requestArray;
        $requestParams = $command->getRequestParams();

        $requestArray = array_merge($requestArray, $requestParams);

        $signArray = array_filter($requestArray, function ($value){return $value !== null && $value !== '';});
        $signature = $this->sign($signArray);
        if($signature === false){
            throw new AlipayClientException('sign failed');
        }
        $systemParams['sign'] = $signature;
        return $systemParams;
    }

    /**
     * 执行命令
     * @param AlipayCommand $command
     * @return array|string
     * @throws AlipayClientException
     */
    public function execute(AlipayCommand $command){

        $systemParams = $this->getParams($command, $requestParams);

        if($command->isResponseAsUrl()){
            $requestArray = array_merge($systemParams, $requestParams);
            $query = http_build_query($requestArray, '', '&', PHP_QUERY_RFC3986);

            return $this->api . '?' . $query;
        }

        $query = http_build_query($systemParams, '', '&', PHP_QUERY_RFC3986);

        $response = $this->getResponse($this->api . '?' . $query, $requestParams, $command);
        if(method_exists($command, 'getResponse')){
            return $command->getResponse($response);
        }
        return $response;
    }

    /**
     * @param string $url
     * @param array $requestParams
     * @return Request|UploadRequest
     * @throws \Exception
     */
    private function createRequest(string $url, array $requestParams){
        $exists = array_filter($requestParams, function ($value){return is_string($value) && !empty($value) && $value[0] === '@';});
        if($exists) {
            $request = new UploadRequest($url);
            foreach ($requestParams as $key => $value){
                if($value === null || $value === '') continue;
                if($value[0] === '@'){
                    $request->addFileField($key, substr($value, 1));
                    continue;
                }
                $request->addField($key, $value);
            }
        }else{
            $request = new Request($url, 'POST');
            $request->setBody($requestParams, 'application/x-www-form-urlencoded');
        }
        return $request;
    }

    /**
     * 获取响应
     * @param string $url
     * @param array $requestParams
     * @param AlipayCommand $command
     * @return array
     * @throws AlipayClientException
     * @throws \Exception
     */
    public function getResponse(string $url, array $requestParams, AlipayCommand $command)
    {
        $request = $this->createRequest($url, $requestParams);

        $httpResponse = $request->getResponse(['sslVerifyPeer' => false, 'sslVerifyHost' => false]);
        if($httpResponse->getStatusCode() != 200){
            throw new AlipayClientException(sprintf('response error, status code: %s', $httpResponse->getStatusCode()));
        }
        $responseText = $httpResponse->getBody();
        if (!$responseText) {
            throw new AlipayClientException('response error');
        }
        $topResponse = json_decode($responseText, true);
        if (!$topResponse) {
            throw new AlipayClientException('json parse error, response is not json?');
        }
        try {

            $response = $this->checkSignature($command, $responseText, $topResponse);
            return $this->parseResponse($response);
        } catch (\Exception $ex) {
            throw new AlipayClientException($ex->getMessage(), $topResponse, $requestParams, $url);
        }
    }

    /**
     * 解析响应数据
     * @param array $response
     * @return array
     * @throws AlipayClientException
     */
    private function parseResponse(array $response)
    {
        if ($response['code'] !== '10000') {
            $errDescription = $response['code'] . ':' . $response['msg'];
            if (isset($response['sub_code'])) {
                $errDescription .= ';' . $response['sub_code'];
            }
            if (isset($response['sub_msg'])) {
                $errDescription .= '(' . $response['sub_msg'] . ')';
            }
            throw new AlipayClientException($errDescription, $response);
        }
        return $response;
    }


    /**
     * 检查接口同步返回的签名
     * @param AlipayCommand $command
     * @param string $responseText
     * @param array $response
     * @return mixed
     * @throws \Exception
     */
    private function checkSignature(AlipayCommand $command, string $responseText, array $response){
        $signature = $response['sign'] ?? null;

        $startKey = $command->getResponseKey();
        if(!isset($response[$startKey])){
            $startKey = 'error_response';
            if(!isset($response[$startKey])){
                throw new \Exception('unexpected response data');
            }
        }
        $response = $response[$startKey];

        $signData = $this->getSignData($startKey, $responseText);

        if(!empty($response['sub_code']) || (empty($response['sub_code']) && !empty($signature))) {
            $result = $this->verifyRaw($signData, base64_decode($signature));
            if (!$result) {
                if (strpos($signData, "\\/") !== false) {
                    $signData = str_replace("\\/", "/", $signData);
                    $result = $this->verifyRaw($signData, base64_decode($signature));
                }
            }
            if(!$result){
                throw new \Exception('signature check fail');
            }
        }
        return $response;
    }

    /**
     * @param string $key
     * @param string $text
     * @return false|string
     * @throws \Exception
     */
    private function getSignData(string $key, string $text){
        $key = sprintf('"%s"', $key);
        $startIndex = strpos($text, $key);
        $startIndex += strlen($key) + 1;

        $endIndex = strrpos($text, ',"sign"');
        if($endIndex === false) throw new \Exception('unexpected response data');
        return substr($text, $startIndex, $endIndex - $startIndex);
    }

    /**
     * 验证通知签名
     * @param array $data
     * @return array
     * @throws AlipayClientException
     */
    public function verifyData($data = null){
		$sign = $data['sign'];
		unset($data['sign']);
        unset($data['sign_type']);
		$verify = $this->verify($data, base64_decode($sign));
		if(!$verify){
            throw new AlipayClientException('sign error');
		}
		return $data;
    }

    /**
     * 获取待签名字符串
     * @param $params
     * @return string
     */
    private function getSignatureData($params){
	    ksort($params);
	    $result = [];
	    foreach( $params as $key => $value )
	    {
		    if($value === null || $value === '' || $value[0] === '@'){
			    continue;
		    }
	    	$result[] = $key . '=' . $value;
	    }
		return implode('&', $result);
    }

    /**
     * 验证RSA2签名
     * @param $params
     * @param $signature
     * @return bool
     * @throws \Exception
     */
    private function verify($params, $signature){
		$data = $this->getSignatureData($params);
	    return $this->verifyRaw($data, $signature);
    }

    /**
     * 验证原始数据RSA2签名
     * @param $data
     * @param $signature
     * @return bool
     * @throws \Exception
     */
    private function verifyRaw($data, $signature){
        $res = openssl_verify($data, $signature, $this->publicKey(), 'sha256WithRSAEncryption');
        return $res === 1;
    }

    /**
     * RSA2签名
     * @param $params
     * @return bool|string
     * @throws \Exception
     */
    private function sign($params){
		$data = $this->getSignatureData($params);
	    $res = openssl_sign($data, $signature, $this->privateKey(), 'sha256WithRSAEncryption');
	    if($res === false){
		    return false;
	    }
	    return base64_encode($signature);
    }

    /**
     * 判断第一个字符，\x30 代表二进制PEM数据，M 代表base64编码的PEM，- 代表base64编码后增加了前后缀的PEM
     * @param $chr
     * @return bool
     */
    private static function isPemRawData($chr){
        return $chr === "\x30" || $chr === 'M' || $chr === '-';
    }

    /**
     * @param $value
     * @param string $keyType PUBLIC|PRIVATE
     * @return string
     * @throws \Exception
     */
    private static function getKeyData($value, $keyType = 'PUBLIC'){
        $chr = $value[0];
        switch ($chr){
            case "\x30":
                return sprintf("-----BEGIN %s KEY-----\r\n%s\r\n-----END %s KEY-----\r\n", $keyType, base64_encode($value), $keyType);
            case 'M':
                return sprintf("-----BEGIN %s KEY-----\r\n%s\r\n-----END %s KEY-----\r\n", $keyType, trim($value), $keyType);
                break;
            case '-':
                return $value;
            default:
                throw new \Exception('invalid ' . strtolower($keyType) . ' key data');
        }
    }

    /**
     * @param string $returnUrl
     */
    public function setReturnUrl(string $returnUrl): void
    {
        $this->returnUrl = $returnUrl;
    }

    /**
     * @param string $notifyUrl
     */
    public function setNotifyUrl(string $notifyUrl): void
    {
        $this->notifyUrl = $notifyUrl;
    }
}

