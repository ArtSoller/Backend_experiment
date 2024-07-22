<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\CurrencyRateService;
use App\Infrastructure\Database\Entity\Currencies;

class UpdateCurrencyRatesCommand extends Command
{
    protected static $defaultName = 'app:update-currency-rates';

    private $entityManager;
    private $currencyRateService;

    public function __construct(EntityManagerInterface $entityManager, CurrencyRateService $currencyRateService)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->currencyRateService = $currencyRateService;
    }

    protected function configure()
    {
        $this
            ->setDescription('Updates the currency rates from an external API.')
            ->setHelp('This command allows you to update the currency rates from an external API.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Fetching currency rates...');

        // Получаем данные с API
        $rates = $this->currencyRateService->fetchCurrencyRates();

        // Логика сравнения и обновления базы данных
        $repository = $this->entityManager->getRepository(Currencies::class);

        foreach ($rates as $currency => $rate) {
            $existingCurrency = $repository->findOneBy(['name' => $currency]);

            if ($existingCurrency) {
                if ($existingCurrency->getRate() != $rate) {
                    $existingCurrency->setRate($rate);
                    $existingCurrency->setExpert($existingCurrency->getExpert()); // Убедитесь, что значение установлено
                    $this->entityManager->persist($existingCurrency);
                    $output->writeln("Updated rate for $currency: $rate");
                }
            }
        }

        $this->entityManager->flush();
        $this->entityManager->clear(); // Добавьте этот вызов

        $output->writeln('Currency rates updated successfully.');

        return Command::SUCCESS;
    }

}
