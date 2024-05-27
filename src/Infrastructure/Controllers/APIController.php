<?php

namespace App\Infrastructure\Controllers;

use http\Client\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_auth')]
class APIController extends AbstractController
{
    #[Route('/signup', name: 'signup', methods: ['POST'])]
    public function signup(Request $request): JsonResponse
    {

        return $this->json('api/index.html.twig',  );
    }
}
