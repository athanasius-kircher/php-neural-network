<?php

declare(strict_types=1);

namespace App\Command;

use App\Model\DataProvider\Csv;
use App\Model\DataProvider\MindProvider;
use App\Model\Math\Vector;
use App\Model\MintData\MintRow;
use App\Model\NeuralNetwork;
use App\Model\NeuralNetworkPersistence;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

#[AsCommand(
    name: 'nn:mint:test',
    hidden: false,
)]
final class NNTestMintCommand extends Command
{

    public function __construct(
        private NeuralNetworkPersistence $neuralNetworkPersistence
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('file', InputArgument::REQUIRED, 'Test file.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = $input->getArgument('file');
        $dataProvider = new MindProvider(new Csv($path));

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
        if ($loaded === 0) {
            $output->writeln('No trained network loaded');

            return Command::FAILURE;
        }
        $neuralNetwork = $this->neuralNetworkPersistence->load($items[$loaded - 1]);


        /** @var MintRow $item */
        $i = 0;
        $success = 0;
        foreach ($dataProvider->getDataIterator() as $item) {
            $i++;
            $inputVector = new Vector($item->getInputData());
            $expectedValue = $item->getTargetValue();

            $outputVector = $neuralNetwork->query($inputVector);
            $max = -1;
            $maxKey = -1;
            foreach ($outputVector->toArray() as $key => $value) {
                if ($value > $max) {
                    $max = $value;
                    $maxKey = $key;
                }
            }

            $output->writeln(
                sprintf(
                'Testrow %d %s. Expected: %d, got: %d',
                $i,
                $expectedValue === $maxKey ? 'OK' : 'FAIL',
                $expectedValue,
                $maxKey
            ));
            if ($expectedValue === $maxKey) {
                $success++;
            }
        }
        $output->writeln(
            sprintf(
                'Success rate: %s',
                $success / $i
            )
        );


        return Command::SUCCESS;
    }
}