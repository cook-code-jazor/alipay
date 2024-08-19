<?php

namespace Jazor\AliPay\Commands;


use Jazor\AliPay\AlipayCommand;

class AlipayUserCertifyOpenCertify extends AlipayCommand
{

    /***
     * AlipayUserCertifyOpenCertify constructor.
     * @param string|null $certifyId
     */
    public function __construct($certifyId = null)
    {
        $this->method = 'alipay.user.certify.open.certify';
        $this['certify_id'] = $certifyId;
    }

    /***
     * @param null|string $value
     * @return string|void
     */
    public function certifyId($value = null){
        if($value === null) return $this['certify_id'];
        $this['certify_id'] = $value;
    }

    public function getResponse($response)
    {
        if($response instanceof \Jazor\Http\Response){
            return $response->getLocation();
        }
        throw new \Exception('invalid response from alipay');
    }
}
