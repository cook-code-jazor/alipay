<?php


namespace Jazor\AliPay\Commands;


use Jazor\AliPay\AlipayCommand;

/**
 * @package Jazor\AliPay\Commands
 * @method setQrPayMode(int $qr_pay_mode) PC扫码支付的方式。
 * @method setQrcodeWidth(int $qrcode_width) 商户自定义二维码宽度。注：qr_pay_mode=4时该参数有效
 * @method setTimeExpire(string $time_expire) 订单绝对超时时间。格式为yyyy-MM-dd HH:mm:ss。
 * @method setGoodsDetail(array $goods_detail) 订单包含的商品列表信息，json格式。
 * @method setTotalAmount(mixed $total_amount) 订单总金额，单位为元，精确到小数点后两位，取值范围为 [0.01,100000000]。金额不能为0。
 * @method setSubject(string $subject) 订单标题。注意：不可使用特殊字符，如 /，=，& 等。
 */

class AlipayTradePagePay extends AlipayCommand
{
    protected bool $responseAsUrl = true;
    /**
     * 支付宝PC端支付
     * @param $out_trade_no
     */
    public function __construct($out_trade_no)
    {
        $this->method = 'alipay.trade.page.pay';
        $this->product_code = 'FAST_INSTANT_TRADE_PAY';
        $this->out_trade_no = $out_trade_no;
    }
}
