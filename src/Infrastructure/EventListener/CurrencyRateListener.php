<?php
namespace App\Infrastructure\EventListener;

use Doctrine\ORM\Event\PostUpdateEventArgs;
use App\Infrastructure\Database\Entity\Currencies;
use App\Infrastructure\Database\Entity\Rules;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\EmailAlertService;

class CurrencyRateListener
{
    private EntityManagerInterface $entityManager;
    private EmailAlertService $emailAlertService;

    public function __construct(EntityManagerInterface $entityManager, EmailAlertService $emailAlertService)
    {
        $this->entityManager = $entityManager;
        $this->emailAlertService = $emailAlertService;
    }

    public function postUpdate(PostUpdateEventArgs $event): void
    {
        $entity = $event->getObject();

        if ($entity instanceof Currencies) {
            $rules = $this->entityManager->getRepository(Rules::class)->findBy(['currencies' => $entity]);

            foreach ($rules as $rule) {
                if ($entity->getRate() > $rule->getAlertRate() && $rule->getRuleStatus()) {
                    $this->emailAlertService->sendEmailAlert($rule, $entity);
                    $rule->setRuleStatus(false);
                    $this->entityManager->flush();
                }
            }
        }
    }
}
