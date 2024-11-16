<?php

namespace App\Model\DataProvider;

interface ProviderInterface
{
    public function getDataIterator(): \Iterator;
}