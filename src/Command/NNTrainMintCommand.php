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
    name: 'nn:mint:train',
    hidden: false,
)]
final class NNTrainMintCommand extends Command
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
            ->addArgument('file', InputArgument::REQUIRED, 'Training file.')
            ->addArgument('stored_name', InputArgument::REQUIRED, 'Stored network name.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = $input->getArgument('file');
        $dataProvider = new MindProvider(new Csv($path));

        $learningRate = $this->getLearningRate($input, $output);
        $epoches = $this->getEpoches($input, $output);
        $hiddenNodeCount = $this->getHiddenNodeCount($input, $output);

        $network = NeuralNetwork::createWithRandomWeights(
            784,
            $hiddenNodeCount,
            10,
            $learningRate
        );

        /** @var MintRow $item */
        $i = 0;
        $start = time();
        for ($epoche = 0; $epoche < $epoches; $epoche++) {
            $provider = $dataProvider->getDataIterator();
            foreach ($provider as $item) {
                $i++;
                $inputVector = new Vector($item->getInputData());
                $target = new Vector($item->getTargetOutputData());

                $network->train($inputVector, $target);
                if($i % 1000 === 0){
                    $output->writeln('Trained: ' . $i . ' in ' . (time() - $start) . 's' . ' epoch: ' . $epoche . ' of ' . $epoches);
                }
            }
        }

        $this->neuralNetworkPersistence->save($network, $input->getArgument('stored_name'));


        return Command::SUCCESS;
    }

    private function getLearningRate(InputInterface $input, OutputInterface $output): float
    {
        $helper = $this->getHelper('question');
        $question = new Question('Learning rate as x/10 (2)?', 2);

        return ((int)$helper->ask($input, $output, $question)) / 10;
    }

    private function getEpoches(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $question = new Question('Get epoches (1)?', 1);

        return ((int)$helper->ask($input, $output, $question));
    }

    private function getHiddenNodeCount(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $question = new Question('Get hidden node count (100)?', 100);

        return ((int)$helper->ask($input, $output, $question));
    }
}