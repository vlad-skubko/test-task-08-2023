<?php

declare(strict_types=1);

namespace Vlad\Commissions\tests\Service;

use PHPUnit\Framework\TestCase;
use Vlad\Commissions\DTO\Transaction;
use Vlad\Commissions\Service\BinDataProvider;
use Vlad\Commissions\Service\ExchangeRatesProvider;
use Vlad\Commissions\Service\TransactionsProcessor;

class TransactionsProcessorTest extends TestCase
{
    public function testGetCommission(): void
    {
        $transaction = new Transaction('45717360', '100.00', 'EUR');

        // Mock ExchangeRatesProvider
        $exchangeRatesProvider = $this->createMock(ExchangeRatesProvider::class);
        $exchangeRatesProvider->expects($this->once())
            ->method('getExchangeRate')
            ->with($transaction->getCurrency())
            ->willReturn(1.2);

        // Mock BinDataProvider
        $binDataProvider = $this->createMock(BinDataProvider::class);
        $binDataProvider->expects($this->once())
            ->method('getBinInfo')
            ->with($transaction->getBin())
            ->willReturn([
                'country' => ['alpha2' => 'US']
            ]);

        $transactionsProcessor = new TransactionsProcessor($exchangeRatesProvider, $binDataProvider);

        $commission = $transactionsProcessor->getCommission($transaction);

        $this->assertEquals(2.00, $commission->getAmount()->toFloat());
    }
}
