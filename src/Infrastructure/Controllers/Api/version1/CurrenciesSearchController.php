<?php

namespace App\Infrastructure\Controllers\Api\version1;

use App\Infrastructure\Database\Entity\Currencies;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CurrenciesController extends AbstractController
{
    #[Route('/api/version1/currencies', name: 'api_version1_currencies', methods: ['GET'])]
    public function getCurrencies(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $searchTerm = $request->query->get('search');

        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('currency')
            ->from(Currencies::class, 'currency')
            ->where('currency.name LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%');

        $currencies = $queryBuilder->getQuery()->getResult();

        $response = [];
        foreach ($currencies as $currency) {
            $response[] = [
                'id' => $currency->getCurrencyId(),
                'name' => $currency->getName(),
            ];
        }

        return $this->json($response);
    }
}