<?php

namespace App\Infrastructure\Controllers\Api\version1;

use App\Infrastructure\Database\Entity\Users;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class LoginController extends AbstractController
{
    #[Route('/api/v1/login', name: 'api_v1_login', methods: ['GET'])]
    public function login(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
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

        return $this->json(['message' => 'User authenticated successfully']);
    }
}