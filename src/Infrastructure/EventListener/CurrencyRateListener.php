<?php
namespace App\Infrastructure\EventListener;

use Doctrine\ORM\Event\PostUpdateEventArgs;
use App\Infrastructure\Database\Entity\Currencies;
use App\Infrastructure\Database\Entity\Rules;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Doctrine\ORM\EntityManagerInterface;


class CurrencyRateListener
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function postUpdate(PostUpdateEventArgs $event): void
    {
        $entity = $event->getObject();

        if ($entity instanceof Currencies) {
            // Получить все алерты для данной валюты
            $rules = $this->entityManager->getRepository(Rules::class)->findBy(['currencies' => $entity]);

            foreach ($rules as $rule) {
                if ($entity->getRate() < $rule->getAlertRate() && $rule->getRuleStatus()) {
                    $this->sendEmailAlert($rule, $entity);
                    $rule->setRuleStatus(false);
                    $this->entityManager->flush();
                }
            }
        }
    }

    private function sendEmailAlert(Rules $rule, Currencies $currency): void
    {
        $transport = Transport::fromDsn('smtp://samaelasalart1@gmail.com:mkgtalykpbrubbra@smtp.gmail.com:587');
        $mailer = new Mailer($transport);
        $email = (new Email())
            ->from('samaelasalart1@gmail.com')
            ->to('onebelouspiece@gmail.com')
            ->subject('Currency Alert: ' . $currency->getName() . ' Rate Alert')
            ->text('The plain text version of the message.')
            ->html('
            <h1 style="color: #ff0000;">
                The rate of ' . $currency->getName() . ' is below/above ' . $rule->getAlertRate() . '. Current rate: ' . $currency->getRate() . '
            </h1>');

        try {
            $mailer->send($email);
        } catch (TransportExceptionInterface $e) {
        }
    }
}