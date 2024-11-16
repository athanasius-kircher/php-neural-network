<?php

namespace App\Tests;

use App\Model\WeightMatrixGenerator;
use PHPUnit\Framework\TestCase;

class WeightMatrixGeneratorTest extends TestCase
{
    public function testSomething(): void
    {
        $weights = WeightMatrixGenerator::initRandomWeightMatrix(5,  6);

        $this->assertEquals(5, $weights->rows);
        $this->assertEquals(6, $weights->columns);

        $data = $weights->toArray();
        for($i = 0; $i<30; $i++) {
           $row = (int)($i / 6);
           $column = $i % 6;
           $cell = $data[$row][$column];
           $this->assertIsFloat($cell);
           $this->assertGreaterThan(-1.99, $cell);
           $this->assertLessThan(1.99, $cell);
        }
    }
}
