<?php

namespace MoneyTransfer\Src;

class Constants
{
    const EUR_TO_USD_RATIO = 1.1497;
    const EUR_TO_JPY_RATIO = 129.53;
    public static $AVAILABLE_CURRENCIES_WITH_EURO_RATES = array('EUR' => 1, 'USD' => Constants::EUR_TO_USD_RATIO, 'JPY' => Constants::EUR_TO_JPY_RATIO);

    const MAX_CASH_IN_COMMISSION = 5.00;

    const CASH_IN_PERCENTAGE = 0.03;
    const CASH_OUT_PERCENTAGE = 0.3;

    const MIN_CASH_OUT_COMMISSION_LEGAL = 0.50;
    const MAX_CASHOUT_AMOUNT_NATURAL = 1000;
    const MAX_CASHOUT_OPERATIONS_NATURAL = 3;
}