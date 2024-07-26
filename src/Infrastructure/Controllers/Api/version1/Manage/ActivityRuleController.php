<?php

namespace App\Infrastructure\Controllers\Api\version1\Manage;

use App\Infrastructure\Database\Entity\Rules;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActivityRuleController extends AbstractController
{
    #[Route('/api/version1/change_activity_rules/{id}', name: 'api_version1_change_activity_rules', methods: ['PUT'])]
    public function ChangeActivityRule(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $rule = $entityManager->getRepository(Rules::class)->find($id);
        if (!$rule) {
            return $this->json(['message' => 'Rule not found'], Response::HTTP_NOT_FOUND);
        }

        $rule->setRuleStatus(!$rule->getRuleStatus());
        $entityManager->persist($rule);
        $entityManager->flush();

        return $this->json(['message' => 'Rule activity change successfully'], Response::HTTP_OK);
    }
}