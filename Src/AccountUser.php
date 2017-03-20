<?php

namespace MoneyTransfer\Src;

class AccountUser extends MoneyTransfer
{
    public $id;
    public $week_withdrawals;
    public $week_drawn_amount;
    public $user_type;
    public $week_end;

    public function __construct($id, $user_type)
    {
        $this->id = $id;
        $this->user_type = $user_type;
    }

    public function initiateTransaction($transaction_type, $sum, $currency, $date)
    {
        if ($transaction_type == 'cash_in')
            return $this->addMoneyToAccount($sum, $currency);
        if ($this->user_type == 'natural') {
            return $this->resolveNaturalCashout($sum, $currency, $date);
        } else
            return $this->withdrawMoneyFromAccount($this->user_type, $sum, $currency);
    }

    private function resolveDateInterval($date)
    {
        if (strtotime($date) <= strtotime($this->week_end))
            return;
        else {
            $day = date("N", strtotime($date));
            $week_end = date('Y-m-d', strtotime($date . '+' . (7 - $day) . ' days'));
            $this->week_end = $week_end;
            $this->week_withdrawals = 0;
            $this->week_drawn_amount = 0;
        }
    }

    private function resolveNaturalCashout($sum, $currency, $date)
    {
        $this->resolveDateInterval($date);
        $commissions = $this->calculateCommissionsNatural($sum, $currency);
        $this->week_withdrawals++;
        $this->week_drawn_amount += $this->convertCurrencyToEUR($sum, $currency);
        return $commissions;
    }

    private function calculateCommissionsNatural($sum, $currency)
    {
        $converted_amount = $this->convertCurrencyToEUR($sum, $currency);
        if ($this->week_withdrawals >= Constants::MAX_CASHOUT_OPERATIONS_NATURAL || $this->week_drawn_amount >= Constants::MAX_CASHOUT_AMOUNT_NATURAL)
            $commissions = $this->withdrawMoneyFromAccount($this->user_type, $sum, $currency);
        else if ($this->week_drawn_amount + $converted_amount <= Constants::MAX_CASHOUT_AMOUNT_NATURAL)
            $commissions = 0;
        else {
            $amount_to_convert = $this->week_drawn_amount + $converted_amount - Constants::MAX_CASHOUT_AMOUNT_NATURAL;
            $amount_to_commission = $this->convertMoneyToGivenCurrency($amount_to_convert, $currency);
            $commissions = $this->withdrawMoneyFromAccount($this->user_type, $amount_to_commission, $currency);
        }
        return $commissions;
    }
}