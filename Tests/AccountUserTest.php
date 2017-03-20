<?php

require __DIR__ . '/../vendor/autoload.php';

use MoneyTransfer\Src\AccountUser;

class AccountUserTest extends PHPUnit_Framework_TestCase
{
    public function testNaturalUserCashoutAfterFourOperations()
    {
        $account = new AccountUser(1, 'natural');
        $account->initiateTransaction('cash_out', 300, 'EUR', '2017-03-16');
        $account->initiateTransaction('cash_out', 300, 'EUR', '2017-03-17');
        $account->initiateTransaction('cash_out', 300, 'EUR', '2017-03-18');
        $commissions = $account->initiateTransaction('cash_out', 300, 'EUR', '2017-03-19');

        $this->assertEquals(0.9, $commissions);
    }

    public function testNaturalUserCashoutTwoOperationsOverFreeLimit()
    {
        $account = new AccountUser(1, 'natural');
        $account->initiateTransaction('cash_out', 700, 'EUR', '2017-03-16');
        $commissions = $account->initiateTransaction('cash_out', 700, 'EUR', '2017-03-17');
        $this->assertEquals(1.2, $commissions);
    }

    public function testNaturalUserCashoutMaxLimitDifferentWeek()
    {
        $account = new AccountUser(1, 'natural');
        $account->initiateTransaction('cash_out', 300, 'EUR', '2017-03-18');
        $account->initiateTransaction('cash_out', 300, 'EUR', '2017-03-19');
        $commissions = $account->initiateTransaction('cash_out', 600, 'EUR', '2017-03-26');

        $this->assertEquals(0, $commissions);

        $account_jpy = new AccountUser(2, 'natural');
        $commissions = $account_jpy->initiateTransaction('cash_out', 300000, 'JPY', '2017-03-01');

        $this->assertEquals(511.42, $commissions);

        $account_usd = new AccountUser(3, 'natural');
        $commissions = $account_usd->initiateTransaction('cash_out', 2299.4, 'USD', '2017-03-01');

        $this->assertEquals(3.45, $commissions);
    }
}
