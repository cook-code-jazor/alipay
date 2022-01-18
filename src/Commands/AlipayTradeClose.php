<?php


namespace Jazor\AliPay\Commands;


use Jazor\AliPay\AlipayCommand;

/**
 * @package Jazor\AliPay\Commands
 */

class AlipayTradeClose extends AlipayCommand
{
    /**
     * 统一收单线下交易关闭
     *
     * @param string $out_trade_no 订单支付时传入的商户订单号,和支付宝交易号不能同时为空。trade_no,out_trade_no如果同时存在优先取trade_no
     * @param string $trade_no
     */
    public function __construct($out_trade_no, $trade_no = '')
    {
        $this->method = 'alipay.trade.close';
        $this->out_trade_no = $out_trade_no;
        $this->trade_no = $trade_no;
    }
}
