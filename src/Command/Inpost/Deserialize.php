<?php

namespace App\Command\Inpost;

use GuzzleHttp\Client;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'inpost:deserialize',
    description: 'Downloads data from Inpost api and deserialize it.',
)]
class Deserialize extends Command
{
    private Client $client;

    public function __construct() {
        $this->client = new Client();

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

        try {
            $response = $this->client->request("get", sprintf("https://api-shipx-pl.easypack24.net/v1/%s?city=%s", $name , $city));
            $rawResponse = $response->getBody()->getContents();
            $jsonResponse = json_decode($rawResponse, true);
        } catch (\Exception $exception) {
            $output->writeln($exception->getMessage());

            return Command::FAILURE;
        }

        dd($jsonResponse);


        return Command::SUCCESS;
    }
}
