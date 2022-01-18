<?php


namespace Jazor\AliPay\Commands;


use Jazor\AliPay\AlipayCommand;

/**
 * @package Jazor\AliPay\Commands
 * @method setRefundAmount(float $refund_amount);
 * @method setRefundReason(string $refund_reason);
 * @method setOutRequestNo(string $out_request_no);
 */

class AlipayTradeRefund extends AlipayCommand
{
    /**
     * 统一收单线下交易退款
     *
     * @param string $out_trade_no 订单支付时传入的商户订单号,和支付宝交易号不能同时为空。trade_no,out_trade_no如果同时存在优先取trade_no
     * @param float $refund_amount 退款金额
     * @param string $trade_no
     */
    public function __construct($out_trade_no, $refund_amount, $trade_no = '')
    {
        $this->method = 'alipay.trade.refund';
        $this->out_trade_no = $out_trade_no;
        $this->trade_no = $trade_no;
        $this->refund_amount = $refund_amount;
    }
}
