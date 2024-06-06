<?php

namespace App\Infrastructure\Controllers;

use App\Infrastructure\Database\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class APILoginController extends AbstractController
{
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function index(#[CurrentUser] ?Users $user): JsonResponse
    {
        if (null === $user) {
            return $this->json(['message' => 'missing credentials'], Response::HTTP_UNAUTHORIZED);
        }

        $token = new UsernamePasswordToken($user, null, $user->getRoles());
        return $this->json(['token' => $token], Response::HTTP_OK);
    }
}