<?php

require __DIR__ . '/vendor/autoload.php';

final class  NullMatrix extends Phpml\Math\Matrix
{
    public function __construct(int $rows, int $columns)
    {
        $matrix = array_fill(0, $rows, array_fill(0, $columns, 0));
        parent::__construct($matrix);
    }
}

use Phpml\Math\Matrix;

$weightsOutput = new Matrix([
    [2, 3,],
    [1,4,]
]);

$o = new Matrix([
    [1,1],
    [1,1]
]);

print_r($weightsOutput->transpose()->multiply($o)->toArray());

$error = new Matrix([
    [.8],
    [.5]
]);


