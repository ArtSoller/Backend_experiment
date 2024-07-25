<?php
namespace App\Service;

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use App\Infrastructure\Database\Entity\Rules;
use App\Infrastructure\Database\Entity\Currencies;

class EmailAlertService
{
    private Mailer $mailer;

    public function __construct()
    {
        $transport = Transport::fromDsn('smtp://samaelasalart1@gmail.com:mkgtalykpbrubbra@smtp.gmail.com:587');
        $this->mailer = new Mailer($transport);
    }

    public function sendEmailAlert(Rules $rule, Currencies $currency): void
    {
        $email = (new Email())
            ->from('samaelasalart1@gmail.com')
            ->to('onebelouspiece@gmail.com')
            ->subject('Currency Alert: ' . $currency->getName() . ' Rate Alert')
            ->text('The plain text version of the message.')
            ->html('
            <h1 style="color: #ff0000;">
                The rate of ' . $currency->getName() . ' has changed relative to your ' . $rule->getAlertRate() . ' expectation. Current rate: ' . $currency->getRate() . '
            </h1>
            <p>Click the button below to take action:</p>
            <a href="http://localhost:5173/" style="
                display: inline-block;
                padding: 10px 20px;
                font-size: 16px;
                color: white;
                background-color: #007bff;
                text-decoration: none;
                border-radius: 5px;
            ">
                Take Action
            </a>');

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            // Handle the exception as needed
        }
    }
}
