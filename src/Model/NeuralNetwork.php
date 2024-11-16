<?php

declare(strict_types=1);

namespace App\Model;

use App\Model\Math\Matrix;
use App\Model\Math\Vector;

final class NeuralNetwork
{
    private function __construct(
        private Matrix $inputToHiddenMatrix,
        private Matrix $hiddenToOutputMatrix,
        private float $learningRate
    )
    {

    }

    public static function createWithRandomWeights(
        int $inputNodeCount,
        int $hiddenNodeCount,
        int $outputNodeCount,
        float $learningRate
    ): self
    {
        $inputToHiddenMatrix = WeightMatrixGenerator::initRandomWeightMatrix(
            $hiddenNodeCount,
            $inputNodeCount
        );
        $hiddenToOutputMatrix = WeightMatrixGenerator::initRandomWeightMatrix(
            $outputNodeCount,
            $hiddenNodeCount
        );

        return new self(
            $inputToHiddenMatrix,
            $hiddenToOutputMatrix,
            $learningRate
        );
    }

    public function train(Vector $input, Vector $targetOutput): void
    {
        $hiddenWeightedInput = $this->inputToHiddenMatrix->multiplyVector($input);
        $hiddenOutput = $hiddenWeightedInput->sigmoid();

        $outputWeightedInput = $this->hiddenToOutputMatrix->multiplyVector($hiddenOutput);
        $finalOutput = $outputWeightedInput->sigmoid();

        $outputErrors = $targetOutput->subtract($finalOutput);
        $hiddenErrors = $this->hiddenToOutputMatrix->transpose()->multiplyVector($outputErrors);

        $vectorOfOne = new Vector(
            array_fill(0, $finalOutput->length, 1)
        );

        $change = $outputErrors
            ->hadamardProduct($finalOutput)
            ->hadamardProduct(
                ($vectorOfOne->subtract($finalOutput))
            )->multiplyMatrix(
                $hiddenOutput->transpose()
            )->multiplyScalar($this->learningRate);

        $this->hiddenToOutputMatrix->add($change);

        $change = $hiddenErrors
            ->hadamardProduct($hiddenOutput)
            ->hadamardProduct(
                ($vectorOfOne->subtract($hiddenOutput))
            )->multiplyMatrix(
                $input->transpose()
            )->multiplyScalar($this->learningRate);

        $this->inputToHiddenMatrix->add($change);

    }

    public function query(Vector $input): Vector
    {
        $hiddenWeightedInput = $this->inputToHiddenMatrix->multiplyVector($input);
        $hiddenOutput = $hiddenWeightedInput->sigmoid();

        $outputWeightedInput = $this->hiddenToOutputMatrix->multiplyVector($hiddenOutput);

        return $outputWeightedInput->sigmoid();
    }
}