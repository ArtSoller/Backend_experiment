<?php

namespace App\Infrastructure\Controllers\Api\version1\Display;

use App\Infrastructure\Database\Entity\Currencies;
use App\Infrastructure\Database\Entity\Rules;
use App\Infrastructure\Database\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ChartCurrencyController extends AbstractController
{
    #[Route('/api/version1/chart-currencies', name: 'api_version1_chart-currencies', methods: ['GET'])]
    public function getCurrencies(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        // Получаем идентификатор пользователя из запроса
        $userId = $request->query->get('user_id');

        if (!$userId) {
            return $this->json(['message' => 'Missing user_id'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Проверяем, что пользователь существует
        $user = $entityManager->getRepository(Users::class)->find($userId);

        if (!$user) {
            return $this->json(['message' => 'User not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Получаем правила пользователя
        $rules = $entityManager->getRepository(Rules::class)->findBy(['user' => $user]);

        // Собираем идентификаторы валют из правил
        $currencyIds = array_map(function (Rules $rule) {
            return $rule->getCurrency()->getCurrencyId();
        }, $rules);

        // Получаем валюты по идентификаторам
        $currencies = $entityManager->getRepository(Currencies::class)->findBy(['currency_id' => $currencyIds]);

        // Преобразуем валюты в массив
        $currencyArray = array_map(function (Currencies $currency) {
            return [
                'currency_id' => $currency->getCurrencyId(),
                'name' => $currency->getName(),
            ];
        }, $currencies);

        return $this->json($currencyArray, JsonResponse::HTTP_OK);
    }
}
