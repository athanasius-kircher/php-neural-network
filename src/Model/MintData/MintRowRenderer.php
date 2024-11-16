<?php

declare(strict_types=1);

namespace App\Model\MintData;

use HeatMap\HeatMap;

final class MintRowRenderer
{
    public function render(MintRow $row): string
    {
        $heatMap = new HeatMap();

        $heatMap->setSpectrum(['white', 'black']);

        return $heatMap->createWithAbsoluteLimits($row->getPixel2DArray(), 0, 255);
    }
}