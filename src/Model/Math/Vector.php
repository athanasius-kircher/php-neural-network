<?php

declare(strict_types=1);

namespace App\Model\Math;

use App\Model\Exception\InvalidArgumentException;

final readonly class Vector
{
    public readonly int $length;
    public function __construct(
        private array $vector
    )
    {
        $this->length = count($this->vector);
    }

    public function toArray(): array
    {
        return $this->vector;
    }

    public function sigmoid(): Vector
    {
        $outputArray = array_map(static fn (float $x) => 1 / (1 + exp(-$x)), $this->vector);

        return new Vector($outputArray);
    }

    public function subtract(Vector $input): Vector
    {
        if ($this->length !== $input->length) {
            throw new InvalidArgumentException('Elements do not have the same dimension.');
        }

        $result = mat_sub([$this->toArray()], [$input->toArray()]);

        return new Vector($result[0]);
    }

    public function transpose(): Matrix
    {
        return new Matrix([$this->toArray()]);
    }

    public function multiplyMatrix(Matrix $matrix): Matrix
    {
        if ($matrix->rows !== 1) {
            throw new InvalidArgumentException('Matrix can not be multiplied.');
        }
        $matrix = mat_mult((new Matrix([$this->toArray()]))->transpose()->toArray(), $matrix->toArray());

        return new Matrix($matrix);
    }

    public function hadamardProduct(Vector $input): Vector
    {
        if ($this->length !== $input->length) {
            throw new InvalidArgumentException('Elements do not have the same dimension.');
        }

        $result = [];
        $data1 = $this->toArray();
        $data2 = $input->toArray();
        foreach ($data1 as $i => $value) {
            $result[] = $value * $data2[$i];
        }

        return new Vector($result);
    }

    public function get(int $index): float
    {
        return $this->vector[$index];
    }

    public function __toString(): string
    {
        return implode('|', $this->vector);
    }
}