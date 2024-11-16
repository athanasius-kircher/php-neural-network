<?php

declare(strict_types=1);

namespace App\Tests;

use App\Model\DataProvider\Csv;
use App\Model\DataProvider\MindProvider;
use App\Model\MintData\MintRow;
use PHPUnit\Framework\TestCase;

final class MintProviderTest extends TestCase
{
    public function testGetDataIterator(): void
    {
        $provider = new Csv(__DIR__ . '/mnist_test_10.csv');
        $mindProvider = new MindProvider($provider);
        $count = 0;
        foreach ($mindProvider->getDataIterator() as $mindRow) {
            $this->assertInstanceOf(MintRow::class, $mindRow);
            $count++;
        }

        $this->assertEquals(10, $count);

    }
}