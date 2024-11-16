<?php

declare(strict_types=1);

namespace App\Model;

use App\Model\Math\Matrix;

final class WeightMatrixGenerator
{

    public static function initRandomWeightMatrix(
        int $rows,
        int $columns
    ): Matrix {
        $weightArray = [];
        for ($i = 0; $i < $rows; $i++) {
            $row = [];
            for ($j = 0; $j < $columns; $j++) {
                $row[] = stats_rand_gen_normal(.0, pow($columns, -0.5));
            }
            $weightArray[] = $row;
        }

        return new Matrix(
                $weightArray
        );
    }
}