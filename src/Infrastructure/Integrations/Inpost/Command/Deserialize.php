<?php

namespace App\Infrastructure\Integrations\Inpost\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Infrastructure\Integrations\Inpost\Provider\InpostDataProvider;

#[AsCommand(
    name: 'inpost:deserialize',
    description: 'Downloads data from Inpost api and deserialize it.',
)]
class Deserialize extends Command
{
    public function __construct(
        private readonly InpostDataProvider $inpostDataProvider,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the resource')
            ->addArgument('city', InputArgument::REQUIRED, 'The city of the resource');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        $city = $input->getArgument('city');

        if (empty($name) || empty($city)) {
            $output->writeln("Name and City cannot be empty");

            return Command::FAILURE;
        }

        try {
            $dto = $this->inpostDataProvider->getInpostData($name, $city);
        } catch (\Exception $exception) {
            $output->writeln($exception->getMessage());

            return Command::FAILURE;
        }

        $jsonOutput = json_encode($dto, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $output->writeln($jsonOutput);

        return Command::SUCCESS;
    }
}
