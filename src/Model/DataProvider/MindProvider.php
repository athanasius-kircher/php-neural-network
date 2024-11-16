<?php

declare(strict_types=1);

namespace App\Model\DataProvider;

use App\Model\MintData\MintRow;

final class MindProvider implements ProviderInterface
{
    public function __construct(
        private ProviderInterface $provider
    )
    {
    }

    public function getDataIterator(): \Iterator
    {
        foreach ($this->provider->getDataIterator() as $row) {
            yield new MintRow($row);
        }
    }
}