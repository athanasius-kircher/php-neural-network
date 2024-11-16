<?php

namespace App\Model;

interface NeuralNetworkPersistence
{
    public function save(NeuralNetwork $neuralNetwork): void;

    public function load(string $ident): NeuralNetwork;

    public function getStorageList(): array;
}