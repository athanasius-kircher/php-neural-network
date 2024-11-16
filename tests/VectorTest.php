<?php

declare(strict_types=1);

namespace App\Tests;

use App\Model\Math\Matrix;
use App\Model\Math\Vector;
use PHPUnit\Framework\TestCase;

final class VectorTest extends TestCase
{
    public function testVector(): void
    {
        $vector = new Vector([1, 2, 3]);

        $this->assertSame(3, $vector->length);
        $this->assertSame(
            '1|2|3',
            $vector->__toString()
        );
        $this->assertSame(2., $vector->get(1));
    }

    public function testSigmoid(): void
    {
        $vector = new Vector([0, 2, 3]);

        $result = $vector->sigmoid();

        $this->assertSame(
            '0.5|0.88079707797788|0.95257412682243',
            $result->__toString()
        );
    }

    public function testSubtract(): void
    {
        $vector = new Vector([1, 2, 3]);
        $input = new Vector([1, 1, 1]);

        $result = $vector->subtract($input);

        $this->assertSame(
            '0|1|2',
            $result->__toString()
        );
    }

    public function testTranspose(): void
    {
        $vector = new Vector([1, 2, 3]);

        $result = $vector->transpose();

        $this->assertSame(
            '1|2|3' . PHP_EOL ,
            $result->__toString()
        );
        $this->assertSame(
            [[1,2,3]],
            $result->toArray()
        );
    }

    public function testMultiplyMatrix(): void
    {
        $vector = new Vector([1, 2, 3]);
        $matrix = new Matrix(
            [
                [4, 5, 6],
            ]
        );

        $result = $vector->multiplyMatrix($matrix);

        $this->assertSame(
            '4|5|6' . PHP_EOL.
            '8|10|12' . PHP_EOL.
            '12|15|18' . PHP_EOL,
            $result->__toString()
        );
    }

    public function testHadamardProduct(): void
    {
        $vector = new Vector([1, 2, 3]);
        $input = new Vector([5, 6, 7]);

        $result = $vector->hadamardProduct($input);

        $this->assertSame(
            '5|12|21',
            $result->__toString()
        );
    }
}