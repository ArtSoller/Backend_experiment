<?php
namespace App\Infrastructure\EventListener;

use Doctrine\ORM\Event\PostUpdateEventArgs;
use App\Infrastructure\Database\Entity\Currencies;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Psr\Log\LoggerInterface;

class CurrencyRateListener
{
    private MailerInterface $mailer;
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function postUpdate(PostUpdateEventArgs $event): void
    {
        $entity = $event->getObject();

        if ($entity instanceof Currencies && $entity->getName() === 'EURO' && $entity->getRate() < 95) {
            $this->sendEmailAlert($entity);
        }
    }

    private function sendEmailAlert(Currencies $currency): void
    {
        $transport = Transport::fromDsn('smtp://samaelasalart1@gmail.com:mkgtalykpbrubbra@smtp.gmail.com:587');
        $this->mailer = new Mailer($transport);
        $email = (new Email())
            ->from('samaelasalart1@gmail.com')
            ->to('onebelouspiece@gmail.com')
            ->subject('Currency Alert: EURO Rate Above 95')
            ->text('The plain text version of the message.')
            ->html('
            <h1 style="color: #ff0000;">
                The rate of EURO is above 95. Current rate: ' . $currency->getRate() . '
            </h1>');

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
        }
    }
}
