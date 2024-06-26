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

class RegistrationController extends AbstractController
{
    #[Route('/api/version1/registration', name: 'api_version1_registration', methods: ['POST'])]
    public function register(Request $request,
                             UserPasswordHasherInterface $passwordHasher,
                             EntityManagerInterface $entityManager,
                             JWTTokenManagerInterface $jwtManager): Response
    {
        $email = $request->query->get('email');
        $password = $request->query->get('password');
        $username = $request->query->get('username');

        if (!$email || !$password || !$username) {
            return $this->json(['message' => 'Missing required parameters'], Response::HTTP_BAD_REQUEST);
        }

        $existingUser = $entityManager->getRepository(Users::class)->findOneBy(['email' => $email]);
        if ($existingUser){
            return $this->json(['message' => 'User with this credentials already exist'], Response::HTTP_CONFLICT);
        }

        $user = new Users();
        $user->setEmail($email);
        $user->setPassword(
            $passwordHasher->hashPassword($user, $password)
        );
        $user->setUsername($username);

        $entityManager->persist($user);
        $entityManager->flush();

        $token = $jwtManager->createFromPayload($user,
            ['expires_in' => '3600',
                'user_id' => $user->getUserId()->toRfc4122(), # 01HZQTM6EY3Y23J57HN9207V99 -> 018fee3d-8585-7020-77e6-e68c66e393b2
                'email' => $email]);
        return $this->json(['message' => 'User created successfully',
            'access_token' => $token],
            Response::HTTP_CREATED);
    }
}