<?php

require __DIR__ . '/vendor/autoload.php';

use MoneyTransfer\Src\AccountUser;

$f = openFile($argv[1]);

if ($f) {
    calculateCommissions($f);
    fclose($f);
}

function openFile($filename)
{
    if (!file_exists($filename))
        throw new Exception('File not found.');
    $f = fopen($filename, 'r');
    return $f;
}

function calculateCommissions($f)
{
    $account_users = array();
    while ($line = fgets($f)) {
        $row_data = explode(',', $line);
        $id = $row_data[1];
        $user_type = $row_data[2];
        $transaction_type = $row_data[3];
        $sum = $row_data[4];
        $currency = trim($row_data[5]);
        $date = $row_data[0];
        $account_user = null;
        if (!isset($account_users[$id])) {
            $account_users[$id] = new AccountUser($id, $user_type);
        }
        $account_user = $account_users[$id];
        echo $account_user->initiateTransaction($transaction_type, $sum, $currency, $date) . "\n";
        $account_users[$id] = $account_user;
    }
}
