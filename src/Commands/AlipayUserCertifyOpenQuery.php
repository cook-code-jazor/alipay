<?php

namespace Jazor\AliPay\Commands;


use Jazor\AliPay\AlipayCommand;

class AlipayUserCertifyOpenQuery extends AlipayCommand
{

    protected string $responseKey = 'alipay_user_certify_open_query_response';
    /***
     * AlipayUserCertifyOpenCertify constructor.
     * @param string|null $certifyId
     */
    public function __construct($certifyId = null)
    {
        $this->method = 'alipay.user.certify.open.query';
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
}
