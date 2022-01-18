<?php


namespace Jazor\AliPay\Commands;


use Jazor\AliPay\AlipayCommand;

/**
 * @package Jazor\AliPay\Commands
 * @method setTimeExpire(string $time_expire) 订单绝对超时时间。格式为yyyy-MM-dd HH:mm:ss。
 * @method setGoodsDetail(array $goods_detail) 订单包含的商品列表信息，json格式。
 * @method setTotalAmount(mixed $total_amount) 订单总金额，单位为元，精确到小数点后两位，取值范围为 [0.01,100000000]。金额不能为0。
 * @method setSubject(string $subject) 订单标题。注意：不可使用特殊字符，如 /，=，& 等。
 */

class AlipayTradeAppPay extends AlipayCommand
{
    /**
     * 支付宝APP端支付
     * @param $out_trade_no
     */
    public function __construct($out_trade_no)
    {
        $this->method = 'alipay.trade.app.pay';
        $this->product_code = 'QUICK_MSECURITY_PAY';
        $this->out_trade_no = $out_trade_no;
    }
}
