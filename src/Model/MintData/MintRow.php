<?php

declare(strict_types=1);

namespace App\Model\MintData;

final class MintRow
{
    private const ROW_LENGTH = 785;

    public function __construct(
        private array $row
    )
    {
        if (count($row) !== self::ROW_LENGTH) {
            throw new \InvalidArgumentException('Invalid row');
        }
    }

    public function getPixel2DArray(): array
    {
        $pixels = array_slice($this->row, 1);
        $pixels = array_chunk($pixels, 28);
        return $pixels;
    }

    public function getInputData(): array
    {
        $pixels = array_slice($this->row, 1);

        return array_map(fn($pixel) => (int)$pixel / 255 * 0.99 + 0.01, $pixels);
    }

    public function getTargetOutputData(): array
    {
        $target = array_fill(0, 10, 0.01);
        $target[(int)$this->row[0]] = 0.99;

        return $target;
    }

    public function getTargetValue(): int
    {
        return (int)$this->row[0];
    }
}