<?php

use MoneyTransfer\Src\MoneyTransfer;

class MoneyTransferTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Exception
     */
    public function testAddLessThanOneMoneyToAccount_ExpectException()
    {
        $transferer = new MoneyTransfer();
        $transferer->addMoneyToAccount(0, "EUR");
        $transferer->addMoneyToAccount(-1, "EUR");
    }

    /**
     * @expectedException Exception
     */
    public function testAddWrongCurrencyMoney_ExpectException()
    {
        $transferer = new MoneyTransfer();
        $transferer->addMoneyToAccount(100, "LTL");
    }

    public function testAddMoneyToAccount()
    {
        $transferer = new MoneyTransfer();

        $commissions = $transferer->addMoneyToAccount(100, "EUR");
        $this->assertEquals(0.03, $commissions);

        $commissions = $transferer->addMoneyToAccount(16633.33, "EUR");
        $this->assertEquals(4.99, $commissions);

        $commissions = $transferer->addMoneyToAccount(100, "USD");
        $this->assertEquals(0.03, $commissions);

        $commissions = $transferer->addMoneyToAccount(20000, "USD");
        $this->assertEquals(5.75, $commissions);

        $commissions = $transferer->addMoneyToAccount(100, "JPY");
        $this->assertEquals(0.03, $commissions);

        $commissions = $transferer->addMoneyToAccount(2250000, "JPY");
        $this->assertEquals(647.65, $commissions);
    }

    public function testWithdrawMoneyFromLegalAccount()
    {
        $transferer = new MoneyTransfer();
        $commissions = $transferer->withdrawMoneyFromAccount("legal", 200, "EUR");
        $this->assertEquals(0.6, $commissions);

        $commissions = $transferer->withdrawMoneyFromAccount("legal", 100, "EUR");
        $this->assertEquals(0.5, $commissions);

        $commissions = $transferer->withdrawMoneyFromAccount("legal", 200, "USD");
        $this->assertEquals(0.6, $commissions);

        $commissions = $transferer->withdrawMoneyFromAccount("legal", 100, "USD");
        $this->assertEquals(0.58, $commissions);

        $commissions = $transferer->withdrawMoneyFromAccount("legal", 26000, "JPY");
        $this->assertEquals(78, $commissions);

        $commissions = $transferer->withdrawMoneyFromAccount("legal", 100, "JPY");
        $this->assertEquals(64.77, $commissions);
    }

    public function testWithdrawMoneyFromNaturalAccount()
    {
        $transferer = new MoneyTransfer();

        $commissions = $transferer->withdrawMoneyFromAccount("natural", 200, "EUR");
        $this->assertEquals(0.6, $commissions);

        $commissions = $transferer->withdrawMoneyFromAccount("natural", 200, "JPY");
        $this->assertEquals(0.6, $commissions);

        $commissions = $transferer->withdrawMoneyFromAccount("natural", 200, "USD");
        $this->assertEquals(0.6, $commissions);
    }
}
