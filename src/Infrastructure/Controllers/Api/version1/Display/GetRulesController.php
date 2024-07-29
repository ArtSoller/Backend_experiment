<?php

namespace App\Infrastructure\Controllers\Api\version1\Display;

use App\Infrastructure\Database\Entity\Rules;
use App\Infrastructure\Database\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GetRulesController extends AbstractController
{
    #[Route('/api/version1/get-rules', name: 'api_version1_get_rules', methods: ['GET'])]
    public function getRules(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $userId = $request->query->get('user_id');

        if (!$userId) {
            return $this->json(['message' => 'Missing user_id'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $user = $entityManager->getRepository(Users::class)->find($userId);

        if (!$user) {
            return $this->json(['message' => 'User not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $rules = $entityManager->getRepository(Rules::class)->findBy(['user' => $user]);

        $rulesData = array_map(function (Rules $rule) {
            return [
                'id' => $rule->getId(),
                'user_id' => $rule->getUser()->getUserId(),
                'currency' => [
                    'currency_id' => $rule->getCurrency()->getCurrencyId(),
                    'name' => $rule->getCurrency()->getName(),
                    'rates' => $rule->getCurrency()->getRates(),
                ],
                'upper_alert_rate' => $rule->getUpperAlertRate(),
                'lower_alert_rate' => $rule->getLowerAlertRate(),
                'rule_status' => $rule->getRuleStatus(),
            ];
        }, $rules);

        return $this->json($rulesData, JsonResponse::HTTP_OK);
    }
}