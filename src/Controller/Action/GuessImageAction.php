<?php

declare(strict_types=1);

namespace App\Controller\Action;
use App\Model\Math\Vector;
use App\Model\MintData\MintRow;
use App\Model\NeuralNetworkPersistence;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/guess-image', name: 'guess_image')]
final class GuessImageAction
{
    private const TRAINED_MODEL = 'mint_large';

    public function __construct(
        private NeuralNetworkPersistence $neuralNetworkPersistence
    )
    {
        $e = 1;
    }

    public function __invoke(Request $request): Response
    {
        $requestContent = $request->getContent();
        $image = json_decode($requestContent, true)['image'] ?? null;
        if (null === $image) {
            throw new BadRequestException('No image in valid format given.');
        }
        $dataUriParts = parse_url($image);
        $imageBinary = base64_decode(str_replace('image/jpeg;base64,','', $dataUriParts['path']));
        $tempFile = sys_get_temp_dir() . '/upload_img_' . time();
        file_put_contents($tempFile, $imageBinary);
        $jpegRessource = imagecreatefromjpeg($tempFile);

        $tempFile = sys_get_temp_dir() . '/img_prepared' . time();
        $dest = imagecreate(28, 28);
        imagecopyresized(
            $dest,
            $jpegRessource,
            0,
            0,
            0,
            0,
            28,
            28,
            imagesx($jpegRessource),
            imagesy($jpegRessource)
        );
        imagefilter($dest, IMG_FILTER_GRAYSCALE);
        imagefilter($dest, IMG_FILTER_CONTRAST, -100);

        imagejpeg($dest, $tempFile);
        $pixelArray = [];
        for ($y = 0; $y < 28; $y++) {
            for ($x = 0; $x < 28; $x++) {
                $index = imagecolorat($dest, $x, $y);
                $rgb = array_values(imagecolorsforindex($dest, $index));
                if ($rgb[0] !== $rgb[1]) {
                    throw new \Exception('No gray scale it seems.');
                }
                $pixelArray[] = 255 - $rgb[0];
            }
        }
        $input = new MintRow(
            [-1, ...$pixelArray]
        );
        $inputVector = new Vector($input->getInputData());
        $neuralNetwork = $this->neuralNetworkPersistence->load(self::TRAINED_MODEL);

        $ouput = $neuralNetwork->query($inputVector);
        $relevantValues = array_filter($ouput->toArray(), static fn ($v) => $v > .1);
        $result = -1;
        if (count($relevantValues) === 1 && current($relevantValues) > .6) {
            $result = key($relevantValues);
        }


        return new Response((string)$result);
    }

}