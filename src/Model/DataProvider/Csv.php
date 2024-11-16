<?php

declare(strict_types=1);

namespace App\Model\DataProvider;

final class Csv implements ProviderInterface
{
    private $file = null;

    public function __construct(
        private string $path,
    ) {
    }

    public function getDataIterator(): \Iterator
    {
        $this->openFile();
        $data = fgetcsv($this->file);

        while (false !== $data) {
            yield $data;
            $data = fgetcsv($this->file);
        }
    }

    private function openFile(): void
    {
        if (null === $this->file) {
            $this->file = fopen($this->path, 'r');
        }
        if (false === $this->file) {
            throw new \RuntimeException('Unable to open file');
        }
    }

    public function __destruct()
    {
        if (null !== $this->file) {
            fclose($this->file);
        }
    }
}