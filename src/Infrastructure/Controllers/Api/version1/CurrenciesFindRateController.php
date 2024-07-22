<?php
namespace App\Infrastructure\Controllers\Api\version1;

use App\Infrastructure\Database\Repository\CurrenciesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CurrenciesFindRateController extends AbstractController
{
    private $currenciesRepository;

    public function __construct(CurrenciesRepository $currenciesRepository)
    {
        $this->currenciesRepository = $currenciesRepository;
    }

    #[Route('/api/version1/exchange-rate', name: 'api_version1_exchange_rate', methods: ['GET'])]
    public function getExchangeRate(Request $request): JsonResponse
    {
        $currencyId = $request->query->get('currency_id');

        if (!$currencyId) {
            return new JsonResponse(['error' => 'currency_id is required'], 400);
        }

        $rate = $this->currenciesRepository->findRateByCurrencyId((int) $currencyId);

        if ($rate === null) {
            return new JsonResponse(['error' => 'Currency not found'], 404);
        }

        return new JsonResponse(['rate' => $rate], 200);
    }
}
