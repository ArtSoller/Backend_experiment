<?php
namespace App\Infrastructure\Controllers;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Infrastructure\Database\Entity\Currencies;

class updateCurrencyController extends AbstractController
{
    public function updateCurrencyRate(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Получить название валюты и курс из запроса
        $currencyName = $request->query->get('name');
        $rate = $request->query->get('rate');

        if (is_null($currencyName) || is_null($rate)) {
            return new Response('Currency name or rate parameter is missing', Response::HTTP_BAD_REQUEST);
        }

        // Найти сущность валюты по имени
        $currency = $entityManager->getRepository(Currencies::class)->findOneBy(['name' => $currencyName]);

        if (!$currency) {
            throw $this->createNotFoundException('No currency found for name ' . $currencyName);
        }

        // Обновить значение курса
        $currency->setRate($rate);

        // Сохранить изменения в базе данных
        $entityManager->flush();

        return new Response('Currency rate updated successfully');
    }
}
