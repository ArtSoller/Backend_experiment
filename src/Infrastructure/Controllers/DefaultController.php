<?php

namespace App\Infrastructure\Controllers;

use App\Infrastructure\Database\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_default')]
    public function index(EntityManagerInterface $en): JsonResponse
    {
        $user = (new Users())
            ->setUsername('Nick')
            ->setEmail('eee')
            ->setPassword('123456')
        ;
        $en->persist($user);
        $en->flush();
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Infrastructure/Database/Controller/DefaultController.php',
        ]);
    }
}
