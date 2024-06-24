<?php

namespace App\Infrastructure\Controllers\Api\version1;

use App\Infrastructure\Database\Entity\Users;

use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LoginController extends AbstractController
{
    #[Route('/api/v1/login', name: 'api_v1_login', methods: ['GET'])]
    public function login(Request $request,
                          UserPasswordHasherInterface $passwordHasher,
                          EntityManagerInterface $entityManager,
                          JWTTokenManagerInterface $jwtManager): Response
    {
        $email = $request->query->get('email');
        $password = $request->query->get('password');

        if (!$email || !$password) {
            return $this->json(['message' => 'Missing required parameters'], Response::HTTP_BAD_REQUEST);
        }

        $user = $entityManager->getRepository(Users::class)->findOneBy(['email' => $email]);

        if (!$user || !$passwordHasher->isPasswordValid($user, $password)) {
            return $this->json(['message' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
        }

        $token = $jwtManager->createFromPayload($user,
            ['expires_in' => '3600',
                'user_id' => $user->getId()->toRfc4122(), # 01HZQTM6EY3Y23J57HN9207V99 -> 018fee3d-8585-7020-77e6-e68c66e393b2
                'email' => $email]);
        return $this->json(['message' => 'User authenticated successfully',
            'access_token' => $token],
            Response::HTTP_CREATED);    }
}