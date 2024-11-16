<?php

declare(strict_types=1);

namespace App\Command;

use App\Model\NeuralNetwork;
use App\Model\NeuralNetworkPersistence;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

#[AsCommand(
    name: 'neuronal:network:start',
    hidden: false,
)]
final class NeuronalNetworkStartCommand extends Command
{

    public function __construct(
        private NeuralNetworkPersistence $neuralNetworkPersistence
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $list = $this->neuralNetworkPersistence->getStorageList();
        $output->writeln('Neural networks:');
        $i = 1;
        $items = [];
        foreach ($list as $item) {
            $output->writeln($i . '): ' . $item);
            $items[] = $item;
            $i++;
        }
        $helper = $this->getHelper('question');
        $question = new Question('Want to load one or create one?', 0);

        $loaded = (int)$helper->ask($input, $output, $question);
        if ($loaded > 0) {
            $neuralNetwork = $this->neuralNetworkPersistence->load($items[$loaded - 1]);
        } else {

        }

        return Command::SUCCESS;
    }
}