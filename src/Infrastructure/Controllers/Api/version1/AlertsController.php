<?php

namespace App\Infrastructure\Controllers\Api\version1;

use App\Infrastructure\Database\Entity\Currencies;
use App\Infrastructure\Database\Entity\Users;
use App\Infrastructure\Database\Entity\Alerts;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AlertsController extends AbstractController
{
    #[Route('/api/version1/alerts', name: 'api_version1_alerts', methods: ['POST'])]
    public function addAlert(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
//        $token = $request->headers->get('Authorization');
//        if (!$token) {
//            return $this->json(['message' => 'Missing token'], Response::HTTP_UNAUTHORIZED);
//        }
//
//        $token = str_replace('Bearer ', '', $token);
//        try {
//            $jwtPayload = $jwtManager->parse($token);
//        } catch (JWTDecodeFailureException $e) {
//            return $this->json(['message' => 'Invalid token'], Response::HTTP_UNAUTHORIZED);
//        }
//
//        $userId = $jwtPayload['user_id'];
//        $currencyId = $request->request->get('currency_id');
//        $alertRate = $request->request->get('alert_rate');
//        if (!$currencyId || !$alertRate) {
//            return $this->json(['message' => 'Missing required parameters'], Response::HTTP_BAD_REQUEST);
//        }
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
            return $this->json(['message' => 'User or city not found'], Response::HTTP_NOT_FOUND);
        }

        $alert = new Alerts();
        $alert->setUser($user);
        $alert->setCurrency($currency);
        $alert->setAlertRate($alertRate);

        $entityManager->persist($alert);
        $entityManager->flush();

        return $this->json(['message' => 'Alert added successfully'], Response::HTTP_CREATED);
    }
}