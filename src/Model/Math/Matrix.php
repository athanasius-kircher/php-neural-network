<?php

declare(strict_types=1);

namespace App\Model\Math;

final readonly class Matrix
{
    public readonly int $rows;

    public readonly int $columns;

    public function __construct(
        private array $matrix
    )
    {
        $this->rows = count($this->matrix);
        $this->columns = count($this->matrix[0]);
    }

    public function multiply(Matrix $matrix): self
    {
        $multiplicationMatrix = mat_mult($this->toArray(), $matrix->toArray());

        return new self($multiplicationMatrix);
    }

    public function multiplyScalar(float $scalar): self
    {
        $multiplicationMatrix = mat_mult($this->toArray(), $scalar);

        return new self($multiplicationMatrix);
    }

    public function multiplyVector(Vector $vector): Vector
    {
        // Convert to column representation
        $matrix = (new Matrix([$vector->toArray()]))->transpose();

        $output = mat_mult($this->toArray(), $matrix->toArray());

        return new Vector(array_column($output, 0));
    }

    public function add(Matrix $matrix): Matrix
    {
        $additionMatrix = mat_add($this->toArray(), $matrix->toArray());

        return new self($additionMatrix);
    }

    public function transpose(): self
    {
        if ($this->rows === 1) {
            $matrix = array_map(static function ($el): array {
                return [$el];
            }, $this->matrix[0]);
        } else {
            $matrix = array_map(null, ...$this->matrix);
        }

        return new self($matrix);
    }

    public function toArray(): array
    {
        return $this->matrix;
    }
}