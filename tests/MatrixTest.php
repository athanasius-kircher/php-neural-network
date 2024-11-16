<?php

declare(strict_types=1);

namespace App\Tests;

use App\Model\Math\Matrix;
use App\Model\Math\Vector;
use PHPUnit\Framework\TestCase;

final class MatrixTest extends TestCase
{
    public function testMatrix(): void
    {
        $matrix = new Matrix(
            [
                [1, 2],
                [3, 4],
            ]
        );

        $this->assertSame(2, $matrix->rows);
        $this->assertSame(2, $matrix->columns);
        $this->assertSame(
            '1|2' . PHP_EOL .
            '3|4'. PHP_EOL,
            $matrix->__toString()
        );
    }

    public function testSkalarMultiplication(): void
    {
        $matrix = new Matrix(
            [
                [1, 2],
                [3, 4],
            ]
        );

        $result = $matrix->multiplyScalar(2);

        $this->assertSame(
            '2|4' . PHP_EOL .
            '6|8'. PHP_EOL,
            $result->__toString()
        );
    }

    public function testTranspose(): void
    {
        $matrix = new Matrix(
            [
                [1, 2, 2],
                [3, 4, 4],
            ]
        );

        $result = $matrix->transpose();

        $this->assertSame(
            '1|3' . PHP_EOL .
            '2|4'. PHP_EOL.
            '2|4'. PHP_EOL,
            $result->__toString()
        );
    }

    public function testMatrixMultiplication(): void
    {
        $matrix = new Matrix(
            [
                [1, 2],
                [3, 4],
            ]
        );

        $result = $matrix->multiply(
            new Matrix(
                [
                    [3, 4],
                    [5, 6],
                ]
            )
        );

        $this->assertSame(
            '13|16' . PHP_EOL .
            '29|36'. PHP_EOL,
            $result->__toString()
        );
    }

    public function testMatrixVectorMultiplication(): void
    {
        $matrix = new Matrix(
            [
                [1, 2],
                [3, 4],
            ]
        );

        $result = $matrix->multiplyVector(
            new Vector([5, 6])
        );

        $this->assertSame(
            '17|39',
            $result->__toString()
        );
    }

    public function testAdd(): void
    {
        $matrix = new Matrix(
            [
                [1, 2],
                [3, 4],
            ]
        );

        $result = $matrix->add(
            new Matrix(
                [
                    [3, 4],
                    [5, 6],
                ]
            )
        );

        $this->assertSame(
            '4|6' . PHP_EOL .
            '8|10'. PHP_EOL,
            $result->__toString()
        );
    }
}