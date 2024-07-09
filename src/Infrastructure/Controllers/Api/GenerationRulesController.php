<?php

namespace App\Infrastructure\Controllers\Api;

use App\Infrastructure\Database\Entity\GenerationRules;
use App\Infrastructure\Database\Repository\GenerationRulesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class GenerationRulesController extends AbstractController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/generation_rules', name: 'generation_rule_index', methods: ['GET'])]
    public function index(GenerationRulesRepository $generationRulesRepository): JsonResponse
    {
//        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $generationRules = $generationRulesRepository->findAll();

        return $this->json([
            'status' => 'success',
            'data' => $generationRules,
        ], JsonResponse::HTTP_OK);
    }

    #[Route('/generation_rules', name: 'generation_rule_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $data = json_decode($request->getContent(), true);

        $generationRule = new GenerationRules();
        $generationRule->setMaxRate($data['max_rate'] ?? '');
        $generationRule->setMinRate($data['min_rate'] ?? []);

        $entityManager->persist($generationRule);
        $entityManager->flush();

        return $this->json([
            'status' => 'success',
            'data' => $generationRule,
        ], JsonResponse::HTTP_CREATED);
    }

    #[Route('/generation_rules/{id}', name: 'generation_rule_show', methods: ['GET'])]
    public function show(GenerationRules $generationRules): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->json([
            'status' => 'success',
            'data' => $generationRules,
        ], JsonResponse::HTTP_OK);
    }

    #[Route('/generation_rules/{id}', name: 'generation_rule_edit', methods: ['PUT'])]
    public function edit(Request $request, GenerationRules $generationRules, EntityManagerInterface $entityManager): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $data = json_decode($request->getContent(), true);

        $generationRules->setMaxRate($data['name'] ?? $generationRules->getName());
        $generationRules->setMinRate($data['rules'] ?? $generationRules->getRules());

        $entityManager->flush();

        return $this->json([
            'status' => 'success',
            'data' => $generationRules,
        ], JsonResponse::HTTP_OK);
    }

    #[Route('/generation_rules/{id}', name: 'generation_rule_delete', methods: ['DELETE'])]
    public function delete(Request $request, GenerationRules $generationRules, EntityManagerInterface $entityManager): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $entityManager->remove($generationRules);
        $entityManager->flush();

        return $this->json([
            'status' => 'success',
        ], JsonResponse::HTTP_NO_CONTENT);
    }
}