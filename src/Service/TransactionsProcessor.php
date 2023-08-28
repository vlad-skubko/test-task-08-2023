<?php

declare(strict_types = 1);

namespace Vlad\Commissions\Service;

use Brick\Math\Exception\MathException;
use Brick\Math\Exception\NumberFormatException;
use Brick\Math\Exception\RoundingNecessaryException;
use Brick\Math\RoundingMode;
use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;
use GuzzleHttp\Exception\GuzzleException;
use Vlad\Commissions\DTO\Transaction;

class TransactionsProcessor
{
    public function __construct(
        private ExchangeRatesProvider $exchangeRatesProvider,
        private BinDataProvider $binDataProvider,
    ) {}

    /**
     * @throws GuzzleException
     * @throws NumberFormatException
     * @throws RoundingNecessaryException
     * @throws UnknownCurrencyException
     * @throws MathException
     */
    public function getCommission(Transaction $transaction): Money
    {
        $amount = Money::of($transaction->getAmount(), $transaction->getCurrency());

        $rate = $this->exchangeRatesProvider->getExchangeRate($amount->getCurrency());

        if (!is_null($rate) && !$amount->getCurrency()->is('EUR')) {
            $amount = $amount->dividedBy($rate, RoundingMode::UP);
        }

        $binResults = $this->binDataProvider->getBinInfo($transaction->getBin());

        $isEu = CountryChecker::isEu($binResults['country']['alpha2']);

        return $isEu ? $amount->multipliedBy('0.01', RoundingMode::UP) : $amount->multipliedBy('0.02', RoundingMode::UP);
    }
}

