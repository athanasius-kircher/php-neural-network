<?php
require __DIR__ . '/../vendor/autoload.php';
//var_dump(mat_mult([[1,0],[0,1]],[[2,4],[3,5]]));
var_dump(mat_mult([[1,0,0],[0,1,0]],[[2],[4],[5]]));
use HeatMap\HeatMap;
use Phpml\Math\Matrix;


function sigmoid($x) {
    return 1 / (1 + exp(-$x));
}

function networkSignalPerLayer(Matrix $input, Matrix $weights): Matrix
{
    $weightedInput = $weights->multiply($input);

    $output = [];
    foreach ($weightedInput->toArray() as $row) {
        $output[] = [sigmoid($row[0])];
    }

    return new Matrix($output);
}

$input = new Matrix(
    [
        [0.9,],
        [0.1,],
        [0.8,]
    ]
);

$weightsHidden = new Matrix([
    [0.9, 0.3, 0.4],
    [0.2, 0.8, 0.2],
    [0.1, 0.5, 0.6]
]);

$weightsOutput = new Matrix([
    [.3, .7, .5],
    [.6, .5, .2],
    [.8, .1, .9]
]);

$outputHidden = networkSignalPerLayer($input, $weightsHidden);
$output = networkSignalPerLayer($outputHidden, $weightsOutput);
//print_r($outputHidden->toArray());
//print_r($output->toArray());

$heatMap = new HeatMap();

$heatMap->setSpectrum(['blue', 'green', 'yellow', 'red']);
$path = $heatMap->createWithAbsoluteLimits($weightsOutput->toArray(), 0, 1);

header('Content-Type: image/png');
header("Content-Disposition: attachment; filename=\"test.png\";" );
echo file_get_contents($path);


