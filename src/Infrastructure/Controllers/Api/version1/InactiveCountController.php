<?php
namespace App\Infrastructure\Controllers\Api\version1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Infrastructure\Database\Entity\Rules;
use App\Infrastructure\Database\Entity\Users;

class InactiveCountController extends AbstractController
{
    #[Route('/api/version1/inactive-count', name: 'inactive_rules_count', methods: ['GET'])]
    public function InactiveRulesCount(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $userId = $request->query->get('user_id');

        if (!$userId) {
            return $this->json(['message' => 'Missing user_id'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $user = $entityManager->getRepository(Users::class)->find($userId);
        if (!$user) {
            return $this->json(['message' => 'User not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $count = $entityManager->getRepository(Rules::class)
            ->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->where('r.user = :user') // Исправлено
            ->andWhere('r.ruleStatus = false')
            ->setParameter('user', $userId) // Исправлено
            ->getQuery()
            ->getSingleScalarResult();

        return new JsonResponse(['count' => $count]);
    }
}
