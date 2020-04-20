<?php

require __DIR__ . '/vendor/autoload.php';

use MoneyTransfer\Src\AccountUser;

$filePath = isset($argv[1]) ? $argv[1] : __DIR__ . '/Data/input.csv';
$file = openFile($filePath);

if ($file) {
    calculateCommissions($file);
    fclose($file);
}

function openFile($filename)
{
    if (!file_exists($filename))
        throw new Exception('File not found.');
    $file = fopen($filename, 'r');
    return $file;
}

function calculateCommissions($file)
{
    $account_users = array();
    while ($line = fgets($file)) {
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
