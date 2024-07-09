<?php
//
//
//namespace App\Infrastructure\Controllers\Contact;
//
//use App\Notification\ContactNotification;
//use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
//use Symfony\Component\Routing\Annotation\Route;
//
//class ContactController extends AbstractController
//{
//    /**
//     * @throws TransportExceptionInterface
//     */
//    #[Route('/contact', name: 'contact', methods: ['POST'])]
//    public function contact(ContactNotification $notification, Request $request): Response
//    {
//        // Получение данных из тела запроса
//        $recipientEmail = $request->request->get('email');
//        $message = $request->request->get('message');
//
//        // Валидация email и сообщения
//        if (empty($recipientEmail) || empty($message)) {
//            return $this->json(['error' => 'Email and message are required'], Response::HTTP_BAD_REQUEST);
//        }
//
//        if (!filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
//            return $this->json(['error' => 'Invalid email address'], Response::HTTP_BAD_REQUEST);
//        }
//
//        // Отправка уведомления
//        $notification->sendNotification($recipientEmail, $message);
//
//        // Ответ с успешным статусом
//        return $this->json(['success' => 'Your message has been sent.'], Response::HTTP_OK);
//    }
//}