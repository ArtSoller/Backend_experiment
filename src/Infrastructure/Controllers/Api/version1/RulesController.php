<?php

namespace App\Infrastructure\Controllers\Api\version1;

use App\Infrastructure\Database\Entity\Currencies;
use App\Infrastructure\Database\Entity\Users;
use App\Infrastructure\Database\Entity\Rules;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AlertsController extends AbstractController
{
    #[Route('/api/version1/rules', name: 'api_version1_rules', methods: ['POST'])]
    public function addRule(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['currency_id'], $data['alert_rate'])) {
            return $this->json(['message' => 'Missing required parameters'], Response::HTTP_BAD_REQUEST);
        }

        $userId = $data['user_id'];
        $currencyId = $data['currency_id'];
        $alertRate = $data['alert_rate'];


        $user = $entityManager->getRepository(Users::class)->find($userId);
        $currency = $entityManager->getRepository(Currencies::class)->find($currencyId);
        if (!$user || !$currency) {
            return $this->json(['message' => 'User or currency not found'], Response::HTTP_NOT_FOUND);
        }

        $alert = new Rules();
        $alert->setUser($user);
        $alert->setCurrency($currency);
        $alert->setAlertRate($alertRate);

        $entityManager->persist($alert);
        $entityManager->flush();

        return $this->json(['message' => 'Alert added successfully'], Response::HTTP_CREATED);
    }
}