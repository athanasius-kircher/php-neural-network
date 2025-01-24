<?php

declare(strict_types=1);

namespace App\Model;

use App\Model\Math\Matrix;
use Psr\Cache\CacheItemPoolInterface;

final class NeuralNetworkAPCuPersistence implements NeuralNetworkPersistence
{
    public function __construct(
        private CacheItemPoolInterface $nnPersistenceCache
    )
    {
    }

    public function save(NeuralNetwork $neuralNetwork, string $ident): void
    {
        $data = [
            'inputToHiddenMatrix' => $neuralNetwork->getInputToHiddenMatrix()->toArray(),
            'hiddenToOutputMatrix' => $neuralNetwork->getHiddenToOutputMatrix()->toArray(),
            'learningRate' => $neuralNetwork->getLearningRate()
        ];
        $dataString = json_encode($data, JSON_THROW_ON_ERROR);

        $item = $this->nnPersistenceCache->getItem($ident);
        $item->set($dataString);

        if (false === $this->nnPersistenceCache->save($item)){
            throw new \RuntimeException('Could not save neural network');
        }
        $item = $this->nnPersistenceCache->getItem('nn');
        $list = $item->isHit() ? $item->get() : [];
        $list[] = $ident;
        $list = array_unique($list);
        $item->set($list);
        $this->nnPersistenceCache->save($item);
    }

    public function load(string $ident): NeuralNetwork
    {
        $item = $this->nnPersistenceCache->getItem($ident);
        if (!$item->isHit()) {
            throw new \RuntimeException('Neural network not found');
        }
        $data = json_decode($item->get(), true);

        $inputToHiddenMatrix = new Matrix($data['inputToHiddenMatrix']);
        $hiddenToOutputMatrix = new Matrix($data['hiddenToOutputMatrix']);
        $learningRate = (float)$data['learningRate'];

        return NeuralNetwork::createWithMatrices(
            $inputToHiddenMatrix,
            $hiddenToOutputMatrix,
            $learningRate
        );
    }

    public function getStorageList(): array
    {
        $item = $this->nnPersistenceCache->getItem('nn');
        if ($item->isHit()) {
            return $item->get();
        }

        return [];
    }


}