<?php
namespace App\Service;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CurrencyRateService
{
    private $httpClient;
    private $apiKey;

    public function __construct(HttpClientInterface $httpClient, string $apiKey)
    {
        $this->httpClient = $httpClient;
        $this->apiKey = $apiKey;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function fetchCurrencyRates(): array
    {
        $response = $this->httpClient->request('GET', sprintf('https://openexchangerates.org/api/latest.json?app_id=%s', $this->apiKey));

        $data = $response->toArray();

        $usdToRub = $data['rates']['RUB'];

        $rates = [];
        foreach ($data['rates'] as $currency => $rate) {
            $rates[$currency] = $usdToRub / $rate;
        }

        return $rates;
    }
}

