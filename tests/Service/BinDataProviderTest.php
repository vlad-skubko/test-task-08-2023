<?php

declare(strict_types=1);

namespace Vlad\Commissions\tests\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Vlad\Commissions\Service\BinDataProvider;
use GuzzleHttp\Exception\GuzzleException;

class BinDataProviderTest extends TestCase
{
    public function testGetBinInfo(): void
    {
        $bin = '45717360';
        $expectedResponse = [
            'country' => ['alpha2' => 'US'],
        ];

        $mock = new MockHandler([
            new Response(200, [], json_encode($expectedResponse)),
            new Response(202, ['Content-Length' => 0]),
            new RequestException('Error Communicating with Server', new Request('GET', 'test'))
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $binDataProvider = new BinDataProvider($client);

        try {
            $result = $binDataProvider->getBinInfo($bin);
            $this->assertSame($expectedResponse, $result);
        } catch (GuzzleException $e) {
            $this->fail('Exception should not be thrown for a successful response');
        }
    }
}
