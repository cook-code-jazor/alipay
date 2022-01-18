<?php


namespace Jazor\AliPay\Commands;


use Jazor\AliPay\AlipayCommand;

/**
 * @package Jazor\AliPay\Commands
 * @method setSubject(string $subject) 订单标题。注意：不可使用特殊字符，如 /，=，& 等。
 * @method setBody(string $body) 订单附加信息。如果请求时传递了该参数，将在异步通知、对账单中原样返回，同时会在商户和用户的pc账单详情中作为交易描述展示
 * @method setGoodsDetail(array $goods_detail) 订单包含的商品列表信息，json格式。
 * @method setTotalAmount(mixed $total_amount) 订单总金额，单位为元，精确到小数点后两位，取值范围为 [0.01,100000000]。金额不能为0。
 * @method setTimeExpire(string $time_expire) 订单绝对超时时间。格式为yyyy-MM-dd HH:mm:ss。
 * @method setTimeoutExpress(string $timeout_express) 订单相对超时时间。从交易创建时间开始计算。该笔订单允许的最晚付款时间，逾期将关闭交易。取值范围：1m～15d。
 * @method setSellerId(string $seller_id) 卖家支付宝用户ID。
 * @method setBuyerId(string $buyer_id) 买家支付宝用户ID。
 *
 */

class AlipayTradePay extends AlipayCommand
{
    const PRODUCT_CODE_FACE_TO_FACE = 'FACE_TO_FACE_PAYMENT';
    const PRODUCT_CODE_APP = 'QUICK_MSECURITY_PAY';
    const PRODUCT_CODE_WAP = 'QUICK_WAP_PAY';
    const PRODUCT_CODE_PC = 'FAST_INSTANT_TRADE_PAY';

    /**
     * 支付宝PC端支付
     * @param string $out_trade_no 商户订单号。
     * @param string $scene bar_code当面付条码支付场景；|security_code，当面付刷脸支付场景，对应的auth_code为fp开头的刷脸标识串；
     * @param string $auth_code 支付授权码。
     * @param string $product_code
     */
    public function __construct($out_trade_no, $auth_code, $scene = 'bar_code', $product_code = 'FACE_TO_FACE_PAYMENT')
    {
        $this->method = 'alipay.trade.pay';
        $this->scene = $scene;
        $this->product_code = $product_code;
        $this->out_trade_no = $out_trade_no;
        $this->auth_code = $auth_code;
    }
}
