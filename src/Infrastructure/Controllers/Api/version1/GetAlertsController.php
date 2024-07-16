<?php

namespace App\Infrastructure\Controllers\Api\version1;

use App\Infrastructure\Database\Entity\Alerts;
use App\Infrastructure\Database\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GetAlertsController extends AbstractController
{
    #[Route('/api/version1/get-alerts', name: 'api_version1_get_alerts', methods: ['GET'])]
    public function getAlerts(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $userId = $request->query->get('user_id');

        if (!$userId) {
            return $this->json(['message' => 'Missing user_id'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $user = $entityManager->getRepository(Users::class)->find($userId);

        if (!$user) {
            return $this->json(['message' => 'User not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $alerts = $entityManager->getRepository(Alerts::class)->findBy(['user' => $user]);

        $alertsData = array_map(function (Alerts $alert) {
            return [
                'id' => $alert->getId(),
                'user_id' => $alert->getUser()->getUserId(),
                'currency' => [
                    'name' => $alert->getCurrency()->getName(),
                ],
                'alert_rate' => $alert->getAlertRate(),
            ];
        }, $alerts);

        return $this->json($alertsData, JsonResponse::HTTP_OK);
    }
}