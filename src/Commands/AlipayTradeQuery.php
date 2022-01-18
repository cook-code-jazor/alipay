<?php


namespace Jazor\AliPay\Commands;


use Jazor\AliPay\AlipayCommand;

/**
 * @package Jazor\AliPay\Commands
 */

class AlipayTradeQuery extends AlipayCommand
{
    /**
     * 统一收单线下交易查询
     *
     * @param string $out_trade_no 订单支付时传入的商户订单号,和支付宝交易号不能同时为空。trade_no,out_trade_no如果同时存在优先取trade_no
     * @param string $trade_no
     */
    public function __construct($out_trade_no, $trade_no = '')
    {
        $this->method = 'alipay.trade.query';
        $this->out_trade_no = $out_trade_no;
        $this->trade_no = $trade_no;
    }
}
