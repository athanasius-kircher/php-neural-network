<?php

declare(strict_types=1);

namespace App\Controller\Action;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/show-canvas', name: 'show_canvas')]
final class ShowCanvasInput
{
    public function __invoke(): Response
    {
        return new Response(sprintf('
<link rel="stylesheet" href="/assets/canvas.css">
<div id="canvas_div" style="overflow-x: auto;">
<canvas id="canvas" width="280" height="280"></canvas>
<button onclick="javascript:clearArea();return false;">Clear Area</button>
Line width : <select id="selWidth">
    <option value="12" selected="selected">12</option>
</select>
<button onclick="javascript:nnApply();return false;">Try to guess</button>
</div>
<div style="width: 100px; height: 50px; border: 1px solid black;" id="result"></div>
<script src="/assets/canvas.js"></script>
'));
    }

}