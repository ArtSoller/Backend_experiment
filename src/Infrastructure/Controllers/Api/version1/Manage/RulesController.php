<?php

namespace App\Infrastructure\Controllers\Api\version1\Manage;

use App\Infrastructure\Database\Entity\Currencies;
use App\Infrastructure\Database\Entity\Rules;
use App\Infrastructure\Database\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RulesController extends AbstractController
{
    #[Route('/api/version1/rules', name: 'api_version1_rules_add', methods: ['POST'])]
    public function addRule(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['currency_id'], $data['upper_alert_rate'], $data['lower_alert_rate'])) {
            return $this->json(['message' => 'Missing required parameters'], Response::HTTP_BAD_REQUEST);
        }

        $userId = $data['user_id'];
        $currencyId = $data['currency_id'];
        $upperAlertRate = $data['upper_alert_rate'];
        $lowerAlertRate = $data['lower_alert_rate'];


        $user = $entityManager->getRepository(Users::class)->find($userId);
        $currency = $entityManager->getRepository(Currencies::class)->find($currencyId);
        if (!$user || !$currency) {
            return $this->json(['message' => 'User or currency not found'], Response::HTTP_NOT_FOUND);
        }

        $rule = new Rules();
        $rule->setUser($user);
        $rule->setCurrency($currency);
        $rule->setUpperAlertRate($upperAlertRate);
        $rule->setLowerAlertRate($lowerAlertRate);

        $entityManager->persist($rule);
        $entityManager->flush();

        return $this->json(['message' => 'Rule added successfully'], Response::HTTP_CREATED);
    }

    #[Route('/api/version1/rules/{id}', name: 'api_version1_rules_update', methods: ['PUT'])]
    public function updateRule(int $id, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['currency_id'], $data['upper_alert_rate'], $data['lower_alert_rate'])) {
            return $this->json(['message' => 'Missing required parameters'], Response::HTTP_BAD_REQUEST);
        }

        $currencyId = $data['currency_id'];
        $upperAlertRate = $data['upper_alert_rate'];
        $lowerAlertRate = $data['lower_alert_rate'];

        $rule = $entityManager->getRepository(Rules::class)->find($id);
        if (!$rule) {
            return $this->json(['message' => 'Rule not found'], Response::HTTP_NOT_FOUND);
        }

        $currency = $entityManager->getRepository(Currencies::class)->find($currencyId);
        if (!$currency) {
            return $this->json(['message' => 'Currency not found'], Response::HTTP_NOT_FOUND);
        }

        $rule->setCurrency($currency);
        $rule->setUpperAlertRate($upperAlertRate);
        $rule->setLowerAlertRate($lowerAlertRate);

        $entityManager->flush();

        return $this->json(['message' => 'Rule updated successfully'], Response::HTTP_OK);
    }

    #[Route('/api/version1/rules/{id}', name: 'api_version1_rules_delete', methods: ['DELETE'])]
    public function deleteRule(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $rule = $entityManager->getRepository(Rules::class)->find($id);
        if (!$rule) {
            return $this->json(['message' => 'Rule not found'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($rule);
        $entityManager->flush();

        return $this->json(['message' => 'Rule deleted successfully'], Response::HTTP_OK);
    }
}