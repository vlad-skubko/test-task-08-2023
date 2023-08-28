<?php

declare(strict_types = 1);

namespace Vlad\Commissions\Service;

use Brick\Money\Currency;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

class ExchangeRatesProvider
{
    private ClientInterface $httpClient;

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @throws GuzzleException
     */
    public function getExchangeRate(Currency $currency): ?float
    {
        $response = $this->httpClient->get('http://api.exchangeratesapi.io/latest?access_key=618260ddbef4fe7bf7a18dc077914e75');

        $data = json_decode($response->getBody()->getContents(), true);

        return $data['rates'][$currency->getCurrencyCode()] ?? null;
    }
}
