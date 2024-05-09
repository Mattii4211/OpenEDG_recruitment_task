<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\NBPApiService;
use Symfony\Component\Console\Input\InputOption;
use DateTime;
use DateMalformedStringException;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Enum\Currency;
use App\Service\FillDatabaseService;
use Exception;

#[AsCommand(
    name: 'app:fill-exchange-table',
    description: 'Fill exchange table from NBP API data'
    )
]
final class FillExchangeTableCommand extends Command
{
    private const DATE_START = '2023-01-01';
    private const DATE_END = '2023-12-31';

    public function __construct(
        private NBPApiService $NBPApiService,
        private FillDatabaseService $fillDatabaseService
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption('dateStart', null, InputOption::VALUE_OPTIONAL, 'Set date start to download data from NBP', self::DATE_START);
        $this->addOption('dateEnd', null, InputOption::VALUE_OPTIONAL, 'Set date end to download data from NBP', self::DATE_END);
    }   

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $dateStart = new DateTime($input->getOption('dateStart'));
            $dateEnd = new DateTime($input->getOption('dateEnd'));
        } catch (DateMalformedStringException $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }

        $euroData = $this->NBPApiService->getNBPExchangeData(Currency::EUR, $dateStart, $dateEnd);
        $usdData = $this->NBPApiService->getNBPExchangeData(Currency::USD, $dateStart, $dateEnd);
        $data = array_merge($euroData, $usdData);

        if (count($data)) {
            try {
                $this->fillDatabaseService->insert($data);
            } catch (Exception $e) {
                $io->error($e->getMessage());
                return Command::FAILURE;
            }
    
            $io->success('Added rows: ' . count($data));
            return Command::SUCCESS;
        } else {
            $io->error('Empty data from NBP API!');
            return Command::FAILURE;
        }
    }
}