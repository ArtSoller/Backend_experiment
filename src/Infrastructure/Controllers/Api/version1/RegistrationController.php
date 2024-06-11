<?php

namespace App\Infrastructure\Controllers\Api\version1;

use App\Infrastructure\Database\Entity\Users;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/api/version1/registration', name: 'api_version1_registration', methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $email = $request->query->get('email');
        $password = $request->query->get('password');
        $username = $request->query->get('username');

        if (!$email || !$password || !$username) {
            return $this->json(['message' => 'Missing required parameters'], Response::HTTP_BAD_REQUEST);
        }

        $user = new Users();
        $user->setEmail($email);
        $user->setPassword(
            $passwordHasher->hashPassword($user, $password)
        );
        $user->setUsername($username);

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json(['message' => 'User created successfully']);
    }
}