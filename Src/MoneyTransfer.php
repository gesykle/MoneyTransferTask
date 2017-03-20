<?php

namespace MoneyTransfer\Src;

use Exception;

class MoneyTransfer
{
    const CURRENCY_ROUNDING_POINT = 2;

    public function __construct()
    {
    }

    public function addMoneyToAccount($sum, $currency)
    {
        $this->validateAmount($sum, $currency);
        return $this->calculateCommissions_CashIn($sum, $currency);
    }

    public function withdrawMoneyFromAccount($user_type, $amount, $currency)
    {
        $this->validateAmount($amount, $currency);
        if ($user_type == "legal")
            return $this->calculateCommissions_CashOutLegal($amount, $currency);
        else
            return $this->calculateCommissions_CashOutNatural($amount, $currency);
    }

    private function calculateCommissions_CashIn($sum, $currency)
    {
        $commissions_unrounded = $sum * Constants::CASH_IN_PERCENTAGE / 100;
        $commissions_limit = $this->calculateCommissionsLimit($currency, Constants::MAX_CASH_IN_COMMISSION);
        $commissions_unrounded = $commissions_unrounded > $commissions_limit ? $commissions_limit : $commissions_unrounded;
        return $this->roundCommisions($commissions_unrounded);
    }

    private function calculateCommissions_CashOutLegal($sum, $currency)
    {
        $commissions_unrounded = $sum * Constants::CASH_OUT_PERCENTAGE / 100;
        $commissions_limit = $this->calculateCommissionsLimit($currency, Constants::MIN_CASH_OUT_COMMISSION_LEGAL);
        $commissions_unrounded = $commissions_unrounded < $commissions_limit ? $commissions_limit : $commissions_unrounded;
        return $this->roundCommisions($commissions_unrounded);
    }

    private function calculateCommissions_CashOutNatural($sum, $currency)
    {
        $commissions_unrounded = $sum * Constants::CASH_OUT_PERCENTAGE / 100;
        return $this->roundCommisions($commissions_unrounded);
    }

    private function roundCommisions($commissions_unrounded)
    {
        $pow = pow(10, self::CURRENCY_ROUNDING_POINT);
        return is_int($pow * $commissions_unrounded) ? round($commissions_unrounded, 2) : (ceil($pow * $commissions_unrounded) + ceil($pow * $commissions_unrounded - ceil($pow * $commissions_unrounded))) / $pow;
    }

    private function validateAmount($sum, $currency)
    {
        if ($sum <= 0)
            throw new Exception("Invalid amount was being added to the account. Transaction aborted");
        else if (!$this->isCurrencyAvailable($currency))
            throw new Exception("This type of currency is not supported. Transaction aborted");
    }

    private function isCurrencyAvailable($currency)
    {
        return array_key_exists($currency, Constants::$AVAILABLE_CURRENCIES_WITH_EURO_RATES) ? $currency : false;
    }

    private function calculateCommissionsLimit($currency, $base_limit)
    {
        $currencies = Constants::$AVAILABLE_CURRENCIES_WITH_EURO_RATES;
        if (array_key_exists($currency, $currencies))
            return $base_limit * $currencies[$currency];
    }

    public function convertMoneyToGivenCurrency($amount, $currency)
    {
        $currencies = Constants::$AVAILABLE_CURRENCIES_WITH_EURO_RATES;
        if (array_key_exists($currency, $currencies))
            return $amount * $currencies[$currency];
    }

    public function convertCurrencyToEUR($amount, $currency)
    {
        $currencies = Constants::$AVAILABLE_CURRENCIES_WITH_EURO_RATES;
        if (array_key_exists($currency, $currencies))
            return $amount / $currencies[$currency];
    }
}