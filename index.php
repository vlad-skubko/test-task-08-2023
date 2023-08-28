<?php

declare(strict_types = 1);

require_once 'vendor/autoload.php';

use Brick\Math\Exception\MathException;
use Brick\Math\Exception\NumberFormatException;
use Brick\Money\Exception\UnknownCurrencyException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Vlad\Commissions\Service\BinDataProvider;
use Vlad\Commissions\Service\ExchangeRatesProvider;
use Vlad\Commissions\Service\TransactionsProcessor;
use Vlad\Commissions\DTO\Transaction;

if ($argc !== 2) {
    echo "Usage: php index.php <input_filename> \n";
    exit(1);
}

$inputFilePath = $argv[1];

$encoders = [new JsonEncoder()];
$normalizers = [new ObjectNormalizer()];

$serializer = new Serializer($normalizers, $encoders);
$httpClient = new Client();

$exchangeRatesProvider = new ExchangeRatesProvider($httpClient);
$binDataProvider = new BinDataProvider($httpClient);
$transactionsProcessor = new TransactionsProcessor($exchangeRatesProvider, $binDataProvider);

$transactionsData = explode("\n", file_get_contents($inputFilePath));

foreach ($transactionsData as $transactionData) {
    if (empty($transactionData)) {
        continue;
    }

    $transaction = $serializer->deserialize($transactionData, Transaction::class, 'json');

    try {
        $commission = $transactionsProcessor->getCommission($transaction);
    } catch (GuzzleException|NumberFormatException|UnknownCurrencyException|MathException $exception) {
        echo $exception->getMessage() . PHP_EOL;
        exit(1);
    }

    echo $commission->getAmount()->toFloat() . PHP_EOL;
}
