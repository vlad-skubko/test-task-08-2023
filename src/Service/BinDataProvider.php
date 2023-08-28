<?php

declare(strict_types = 1);

namespace Vlad\Commissions\Service;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

class BinDataProvider
{
    private ClientInterface $httpClient;

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @throws GuzzleException
     */
    public function getBinInfo(string $bin): array
    {
        $response = $this->httpClient->get(sprintf('https://lookup.binlist.net/%s', $bin));

        return json_decode($response->getBody()->getContents(), true);
    }
}
