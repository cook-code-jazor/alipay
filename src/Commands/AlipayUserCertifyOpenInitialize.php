<?php

namespace Jazor\AliPay\Commands;

use Jazor\AliPay\AlipayCommand;

class AlipayUserCertifyOpenInitialize extends AlipayCommand
{
    protected string $responseKey = 'alipay_user_certify_open_initialize_response';
    /***
     * @var string 多因子人脸认证
     */
    public static $BIZ_CODE_FACE = 'FACE';

    /***
     * @var string 多因子证照认证
     */
    public static $BIZ_CODE_CERT_PHOTO = 'CERT_PHOTO';

    /***
     * @var string 多因子证照和人脸认证
     */
    public static $BIZ_CODE_CERT_PHOTO_FACE = 'CERT_PHOTO_FACE';

    /***
     * @var string 多因子快捷认证
     */
    public static $BIZ_CODE_SMART_FACE = 'SMART_FACE';

    /***
     * @var string 身份信息
     */
    public static $IDENTITY_TYPE_CERT_INFO = 'CERT_INFO';


    /***
     * @var string 身份证
     */
    public static $CERT_TYPE_IDENTITY_CARD = 'IDENTITY_CARD';

    /***
     * @var string 港澳通行证
     */
    public static $CERT_TYPE_HOME_VISIT_PERMIT_HK_MC = 'HOME_VISIT_PERMIT_HK_MC';

    /***
     * @var string 台湾通行证
     */
    public static $CERT_TYPE_HOME_VISIT_PERMIT_TAIWAN = 'HOME_VISIT_PERMIT_TAIWAN';

    /***
     * @var string 港澳居住证
     */
    public static $CERT_TYPE_RESIDENCE_PERMIT_HK_MC = 'RESIDENCE_PERMIT_HK_MC';

    /***
     * @var string 台湾居住证
     */
    public static $CERT_TYPE_RESIDENCE_PERMIT_TAIWAN = 'RESIDENCE_PERMIT_TAIWAN';



    private $identity_param = null;
    private $merchant_config = null;

    /***
     * AlipayUserCertifyOpenInitialize constructor.
     * @param string|null $outerOrderNo
     */
    public function __construct($outerOrderNo = null)
    {
        $this->method = 'alipay.user.certify.open.initialize';
        $this['biz_code'] = self::$BIZ_CODE_FACE;
        $this->identity_param = [
            'identity_type' => self::$IDENTITY_TYPE_CERT_INFO,
            'cert_type' => self::$CERT_TYPE_IDENTITY_CARD,
            'cert_name' => '',
            'cert_no' => '',
            'phone_no' => '',
        ];
        $this->merchant_config = [
            'return_url' => '',
        ];
        if($outerOrderNo){
            $this['outer_order_no'] = $outerOrderNo;
        }
    }

    /***
     * @param null|string $value
     * @return string|void
     */
    public function outerOrderNo($value = null){
        if($value === null) return $this['outer_order_no'];
        $this['outer_order_no'] = $value;
    }

    /***
     * @param null|string $value
     * @return string|void
     */
    public function bizCode($value = null){
        if($value === null) return $this['biz_code'];
        $this['biz_code'] = $value;
    }

    /***
     * @param string|null $value
     * @return string|void
     */
    public function identityType($value = null){
        if($value === null) return $this->identity_param['identity_type'];
        $this->identity_param['identity_type'] = $value;
    }

    /***
     * @param string|null $value
     * @return string|void
     */
    public function certType($value = null){
        if($value === null) return $this->identity_param['cert_type'];
        $this->identity_param['cert_type'] = $value;
    }

    /***
     * @param string|null $value
     * @return string|void
     */
    public function certName($value = null){
        if($value === null) return $this->identity_param['cert_name'];
        $this->identity_param['cert_name'] = $value;
    }

    /***
     * @param string|null $value
     * @return string|void
     */
    public function certNo($value = null){
        if($value === null) return $this->identity_param['cert_no'];
        $this->identity_param['cert_no'] = $value;
    }

    /***
     * @param string|null $value
     * @return string|void
     */
    public function phoneNo($value = null){
        if($value === null) return $this->identity_param['phone_no'];
        $this->identity_param['phone_no'] = $value;
    }

    /***
     * @param string|null $value
     * @return string|void
     */
    public function returnUrl($value = null){
        if($value === null) return $this->merchant_config['return_url'];
        $this->merchant_config['return_url'] = $value;
    }

    public function toJson()
    {
        $this['identity_param'] = $this->identity_param;
        $this['merchant_config'] = $this->merchant_config;
        return parent::toJson();
    }
}
