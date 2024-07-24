<?php

namespace App\Infrastructure\Controllers;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

class SendEmailController extends AbstractController
{
    public function sendEmail(): Response
    {
        $transport = Transport::fromDsn('smtp://samaelasalart1@gmail.com:mkgtalykpbrubbra@smtp.gmail.com:587');

        $email = (new Email())
            ->from('samaelasalart1@gmail.com')
            ->to('onebelouspiece@gmail.com')
            ->subject('A Cool Subject!')
            ->text('The plain text version of the message.')
            ->html('
                <h1 style="color: #fff300; background-color: #0073ff; width: 500px; padding: 16px 0; text-align: center; border-radius: 50px;">
                    The HTML version of the message.
                </h1>
                <h1 style="color: #ff0000; background-color: #5bff9c; width: 500px; padding: 16px 0; text-align: center; border-radius: 50px;">
                    The End!
                </h1>
            ');
        $mailer = new Mailer($transport);

        try {
            $mailer->send($email);

            return new Response('<h1>Email sent successfully!</h1>', Response::HTTP_OK);
        } catch (TransportExceptionInterface $e) {
            return new Response('<h1>Error sending email.</h1>', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}