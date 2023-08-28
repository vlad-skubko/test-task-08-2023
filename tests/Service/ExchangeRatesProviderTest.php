<?php

declare(strict_types=1);

namespace Vlad\Commissions\tests\Service;

use Brick\Money\Currency;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Vlad\Commissions\Service\ExchangeRatesProvider;

class ExchangeRatesProviderTest extends TestCase
{
    public function testGetExchangeRate(): void
    {
        $mock = new MockHandler([
            new Response(200, [], '{"rates":{"USD":1.2,"EUR":1.0}}'),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $httpClient = new Client(['handler' => $handlerStack]);

        $exchangeRatesProvider = new ExchangeRatesProvider($httpClient);

        $currency = Currency::of('USD');
        $exchangeRate = $exchangeRatesProvider->getExchangeRate($currency);

        $this->assertEquals(1.2, $exchangeRate);
    }
}
