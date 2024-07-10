<?php
namespace App\Infrastructure\EventListener;

use Doctrine\ORM\Event\PostUpdateEventArgs;
use App\Infrastructure\Database\Entity\Currencies;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;

class CurrencyRateListener
{
private $mailerInterface;

public function __construct(MailerInterface $mailerInterface)
{
$this->mailerInterface = $mailerInterface;
}

public function postUpdate(PostUpdateEventArgs $event): void
{
$entity = $event->getObject();

// Проверяем, если это рубль и курс ниже 86
if ($entity instanceof Currencies && $entity->getName() === 'RUB' && $entity->getRate() < 86) {
$this->sendEmailAlert($entity);
}
}

private function sendEmailAlert(Currencies $currency): void
{
// Создаем транспорт и экземпляр Mailer
$transport = Transport::fromDsn('smtp://samaelasalart1@gmail.com:mkgtalykpbrubbra@smtp.gmail.com:587');
$mailer = new Mailer($transport);

$email = (new Email())
->from('samaelasalart1@gmail.com')
->to('onebelouspiece@gmail.com')
->subject('Currency Alert: RUB Rate Drop')
->text('The plain text version of the message.')
->html('
<h1 style="color: #ff0000;">
    The rate of RUB has dropped below 86. Current rate: ' . $currency->getRate() . '
</h1>
');

try {
$mailer->send($email);
} catch (TransportExceptionInterface $e) {
// Обработка ошибок отправки email
}
}
}
