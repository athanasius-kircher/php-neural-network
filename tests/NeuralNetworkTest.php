<?php

namespace App\Tests;

use App\Model\Math\Vector;
use App\Model\NeuralNetwork;
use PHPUnit\Framework\TestCase;

class NeuralNetworkTest extends TestCase
{
    public function testQuery(): void
    {
        $neuralNetwork = NeuralNetwork::createWithRandomWeights(3,3,3, 0.3);

        $input = new Vector(
            [2, 3, 4]
        );

        $output = $neuralNetwork->query($input);

        $this->assertEquals(3, $output->length);
    }

    public function testTrain(): void
    {
        $neuralNetwork = NeuralNetwork::createWithRandomWeights(3,3,3, 0.3);

        $input = new Vector(
            [2, 3, 4]
        );

        $targetOutput = new Vector(
            [2, 3, 4]
        );

        $neuralNetwork->train($input, $targetOutput);

        $this->assertTrue(true);
    }
}
