<?php

namespace App\Model;

interface NeuralNetworkPersistence
{
    public function save(NeuralNetwork $neuralNetwork, string $ident): void;

    public function load(string $ident): NeuralNetwork;

    public function getStorageList(): array;
}