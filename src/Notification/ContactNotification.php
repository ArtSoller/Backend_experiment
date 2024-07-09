<?php

namespace App\Notification;

use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class ContactNotification
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendNotification(string $recipientEmail, string $message): void
    {
        $email = (new NotificationEmail())
            ->from(new Address('samaelasalart1@google.com', 'Your Name'))
            ->to($recipientEmail)
            ->subject('New message from your website!')
            ->html('<p>' . $message . '</p>');

        $this->mailer->send($email);
    }
}